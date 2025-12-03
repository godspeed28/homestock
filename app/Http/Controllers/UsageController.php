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
        return DB::transaction(function () use ($id) {
            $item = Item::where('id', $id)
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->stock <= 0) {
                return back()->with('error', 'Stok barang sudah habis!');
            }

            $oldStock = $item->stock;

            // Kurangi stock dan dapatkan nilai baru
            $item->decrement('stock');
            $item->refresh();

            // Commit transaksi dulu sebelum dispatch job
            DB::commit();

            // Kirim notifikasi jika stock turun ke atau di bawah minimum
            if (
                $oldStock > $item->minimum_stock &&
                $item->stock <= $item->minimum_stock
            ) {
                // Hanya ambil item yang stock-nya ≤ minimum_stock masing-masing
                $lowStockItems = Item::where('user_id', Auth::id())
                    ->whereColumn('stock', '<=', 'minimum_stock')
                    ->get();

                if ($lowStockItems->isNotEmpty()) {
                    $message = "⚡ *HomeStock Alert* ⚡\n────────────────────\n";
                    $message .= "📌 *Stok Menipis*\n\n";

                    foreach ($lowStockItems as $lowItem) {
                        $stock = round($lowItem->stock);
                        $stockMin = round($lowItem->minimum_stock);
                        $level = $lowItem->stock <= ($lowItem->minimum_stock * 0.5)
                            ? "‼️ Sangat Rendah"
                            : "⚠️ Rendah";

                        $message .= "🛍️ *Barang:* {$lowItem->name}\n";
                        $message .= "📊 *Sisa Stok:* {$stock} {$lowItem->unit}\n";
                        $message .= "📉 *Minimal Stok:* {$stockMin} {$lowItem->unit}\n";
                        $message .= "⏱️ *Level Kritis:* {$level}\n";
                        $message .= "────────────────────\n";
                    }

                    $message .= "💡 *Saran:* Segera lakukan restock agar stok tidak habis.\n";
                    $message .= "🏠 *HomeStock* | Pantau stokmu dengan mudah!";

                    SendWhatsappNotificationJob::dispatch(
                        Auth::user()->whatsapp_number,
                        $message
                    );
                }
            }

            // Dispatch riwayat
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
