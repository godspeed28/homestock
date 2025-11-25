<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\StockUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function itemData()
    {

        $data = StockUsage::selectRaw('MONTH(created_at) as bulan, SUM(amount) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->where('user_id', Auth::id())
            ->get();

        /* $data =  [
            ["bulan" => 1, "total" => 40],
            ["bulan" => 2, "total" => 70],
            ["bulan" => 3, "total" => 20],
            ["bulan" => 4, "total" => 90],
            ["bulan" => 5, "total" => 50],
            ["bulan" => 6, "total" => 80]
        ]; */


        $bulanMap = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];


        $monthlyData = array_fill(1, 12, 0);

        foreach ($data as $row) {
            $bulan = is_array($row) ? $row['bulan'] : $row->bulan;
            $total = is_array($row) ? $row['total'] : $row->total;

            $monthlyData[$bulan] = (int) $total;
        }

        $labels = [];
        $values = [];

        foreach ($monthlyData as $bulan => $total) {
            $labels[] = $bulanMap[$bulan];
            $values[] = $total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $values,
        ]);
    }

    public function topCategoryPie()
    {
        $top = StockUsage::selectRaw('items.category_id, categories.name, SUM(stock_usage.amount) AS total_amount')
            ->join('items', 'stock_usage.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->where('stock_usage.user_id', Auth::id()) // PENTING: taruh sebelum groupBy
            ->groupBy('items.category_id', 'categories.name')
            ->orderByDesc('total_amount')
            ->limit(3)
            ->get();

        return response()->json([
            'labels' => $top->pluck('name'),
            'data' => $top->pluck('total_amount')
        ]);
    }
}
