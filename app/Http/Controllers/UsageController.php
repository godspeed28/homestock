<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\SaveStockUsageJob;
use App\Jobs\SendWhatsappNotificationJob;

class UsageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Item::where('user_id', Auth::id())->get();
        return view('pages.usage.index', compact('items'));
    }

    public function ambil($id)
    {
        // Gunakan database transaction dengan lock untuk menghindari race condition
        return DB::transaction(function () use ($id) {
            $item = Item::where('id', $id)
                ->where('user_id', Auth::id())
                ->lockForUpdate() // Lock row untuk menghindari race condition
                ->firstOrFail();

            if ($item->stock <= 0) {
                return back()->with('error', 'Stok barang sudah habis!');
            }

            // Simpan stock sebelum dikurangi untuk notifikasi
            $oldStock = $item->stock;

            // Kurangi stock
            $item->decrement('stock'); // Gunakan decrement() untuk atomic operation

            // Refresh data item untuk mendapatkan nilai stock terbaru
            $item->refresh();

            // Notifikasi WA masuk queue
            if (
                $oldStock > $item->minimum_stock &&
                $item->stock <= $item->minimum_stock
            ) {
                SendWhatsappNotificationJob::dispatch(
                    Auth::user()->whatsapp_number,
                    "🚨 *PERINGATAN STOK KRITIS* 🚨\n" .
                        "═════════════════════════\n\n" .
                        "📦 *Nama Barang:* {$item->name}\n" .
                        "📉 *Stok Tersisa:* {$item->stock} {$item->unit}\n" .
                        "⚠️ *Level Stok:* " . ($item->stock == 0 ? "HABIS" : "KRITIS") . "\n" .
                        "📋 *Minimum Stok:* {$item->minimum_stock} {$item->unit}\n" .
                        "📊 *Persentase Stok:* " . ($item->maximum_stock > 0 ? round(($item->stock / $item->maximum_stock) * 100, 1) : 0) . "%\n" .
                        "🕐 *Waktu:* " . now()->translatedFormat('l, d F Y H:i:s') . "\n" .
                        "👤 *Diambil oleh:* " . Auth::user()->name . "\n\n" .
                        "🚀 *TINDAKAN DIBUTUHKAN:*\n" .
                        "1️⃣ Lakukan restock segera\n" .
                        "2️⃣ Periksa kebutuhan stok berikutnya\n" .
                        "3️⃣ Update data pembelian\n\n" .
                        "🔔 *CATATAN PENTING:*\n" .
                        "Stok saat ini sudah di ambang batas minimum. Segera hubungi supplier atau lakukan pembelian untuk menghindari kekosongan stok.\n\n" .
                        "📞 *Untuk bantuan:*\n" .
                        "Hubungi admin atau akses sistem untuk update stok.\n\n" .
                        "✅ *Status:* " . ($item->stock == 0 ? "URGENT - STOK HABIS" : "WARNING - STOK KRITIS") . "\n" .
                        "═════════════════════════\n" .
                        "🏠 *HomeStock Inventory System*\n" .
                        "📱 Notifikasi Otomatis"
                );
            }

            // Penyimpanan riwayat masuk queue
            SaveStockUsageJob::dispatch(
                Auth::id(),
                $item->id,
                1,
                'Pengambilan 1 ' . $item->unit . ' ' . $item->name
            );

            return back()->with('success', 'Berhasil mengambil 1 item.');
        });
    }
}
