<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockUsage;
use App\Models\User;
use App\Helpers\WhatsappHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $item = Item::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($item->stock <= 0) {
            return back()->with('error', 'Stok barang sudah habis!');
        }

        $user = User::find(Auth::id());


        $item->stock -= 1;
        $item->save();

        if ($item->stock <= $item->minimum_stock) {
            $stock = round($item->stock);
            Log::info(WhatsappHelper::send(
                $user->whatsapp_number,
                "⚠️ *HomeStock Notifikasi* \n\nStok *{$item->name}* tinggal {$stock} {$item->unit}.\nSegera restock!",
            ));
        }

        StockUsage::create([
            'user_id'    => Auth::id(),
            'item_id'    => $item->id,
            'amount'     => 1,
            'description' => 'Pengambilan 1 ' . $item->unit . ' ' . $item->name,
        ]);

        return back()->with('success', 'Berhasil mengambil 1 item.');
    }
}
