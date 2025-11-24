<?php

namespace App\Http\Controllers;

use App\Models\StockUsage;
use App\Models\StockAddition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockUsageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usages = StockUsage::where('user_id', Auth::id())->get();
        return view('pages.history.index', compact('usages'));
    }

    public function add()
    {
        $items = StockAddition::where('user_id', Auth::id())->get();
        return view('pages.history.add', compact('items'));
    }

    public function destroy(StockUsage $history)
    {
        $history->delete();
        return redirect()->back()->with('success', 'History berhasil dihapus!');
    }

    public function delete(StockAddition $data)
    {
        $data->delete();
        return redirect()->back()->with('success', 'History berhasil dihapus!');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = explode(',', $request->ids);
        StockUsage::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = explode(',', $request->ids);
        StockAddition::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
