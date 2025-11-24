<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\StockAddition;
use App\Helpers\WhatsappHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Item::where('user_id', Auth::id())->get();
        $categories = Category::all()->where('user_id', Auth::id());
        return view('pages.items.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $total_harga = $request->stock * $request->harga_satuan;
        $request->validate([
            'name'           => 'required',
            'stock'          => 'required|integer|min:0',
            'unit'           => 'required',
            'harga_satuan'   => 'required|numeric|min:0',
            'minimum_stock'  => 'required|integer|min:0',
        ]);

        Item::create([
            'user_id'       => Auth::id(),
            'category_id'  => $request->category_id,
            'name'          => $request->name,
            'stock'         => $request->stock,
            'unit'          => $request->unit,
            'harga_satuan'  => $request->harga_satuan,
            'total_harga'   => $total_harga,
            'minimum_stock' => $request->minimum_stock,
            'notif_enabled' => 1,
        ]);

        $item = Item::where('user_id', Auth::id())->first();

        $user = User::find(Auth::id());

        if ($item->stock <= $item->minimum_stock) {
            $stock = round($item->stock);
            Log::info(WhatsappHelper::send(
                $user->whatsapp_number,
                "⚠️ *HomeStock Notifikasi* \n\nStok *{$item->name}* tinggal {$stock} {$item->unit}.\nSegera restock!",
            ));
        }

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        $oldStock = $item->stock;

        $item->update([
            'name'          => $request->name,
            'category_id'   => $request->category_id,
            'stock'         => $request->stock,
            'unit'          => $request->unit,
            'harga_satuan'  => $request->harga_satuan,
            'minimum_stock' => $request->minimum_stock,
        ]);

        // Hitung total harga setelah update
        $total_harga = $item->stock * $item->harga_satuan;

        // Update total harga
        $item->update([
            'total_harga' => $total_harga
        ]);

        $addedAmount = $request->stock - $oldStock;

        if ($addedAmount > 0) {
            StockAddition::create([
                'user_id'    => Auth::id(),
                'item_id'    => $item->id,
                'amount'     => $addedAmount,
                'description' => 'Menambahkan ' . $addedAmount . ' ' . $item->unit . ' ' . $item->name,
            ]);
        }

        $user = User::find(Auth::id());

        if ($item->stock <= $item->minimum_stock) {
            $stock = round($item->stock);
            Log::info(WhatsappHelper::send(
                $user->whatsapp_number,
                "⚠️ *HomeStock Notifikasi* \n\nStok *{$item->name}* tinggal {$stock} {$item->unit}.\nSegera restock!",
            ));
        }

        return redirect()->back()->with('success', 'Item berhasil diperbarui!');
    }


    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Item berhasil dihapus!');
    }
}
