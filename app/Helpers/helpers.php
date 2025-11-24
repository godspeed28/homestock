<?php

use App\Models\Item;
use App\Models\StockAddition;
use App\Models\StockUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if (! function_exists('totalItem')) {
    function totalItem()
    {
        $total = Item::where('user_id', Auth::id())->sum('stock');
        return round($total);
    }
}

if (! function_exists('itemKritis')) {
    function itemKritis()
    {
        $total = Item::where('user_id', Auth::id())
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->count();
        return round($total);
    }
}

if (! function_exists('itemTerpakai')) {
    function itemTerpakai()
    {
        $total = StockUsage::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->count();

        return round($total);
    }
}

if (! function_exists('historyUser')) {
    function historyUser()
    {
        $total1 = StockUsage::where('user_id', Auth::id())->count();
        $total2 = StockAddition::where('user_id', Auth::id())->count();
        $total = $total1 + $total2;
        return round($total);
    }
}

if (! function_exists('rupiah')) {
    function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
}

if (! function_exists('totalHargaItem')) {
    function totalHargaItem()
    {
        $total = Item::where('user_id', Auth::id())->sum('total_harga');
        return $total;
    }
}
