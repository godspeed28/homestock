<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class SearchController extends Controller
{
    public function ajaxSearch(Request $request)
    {
        $query = $request->input('query');

        $items = Item::with('category')->where('name', 'like', "%{$query}%")->limit(5)->get();
        $categories = Category::where('name', 'like', "%{$query}%")->limit(5)->get();

        $html = '';

        if ($items->count()) {
            $html .= '<div class="p-2"><strong>Items</strong></div>';
            foreach ($items as $item) {
                $html .= '
            <a href="' . route('items.index') . '" class="dropdown-item d-flex align-items-center">
                <i class="fas fa-box mr-2 text-primary"></i>
                <div>
                    <div>' . $item->name . '</div>
                    <small class="text-muted">Kategori: ' . $item->category->name . '</small>
                </div>
            </a>';
            }
        }

        if ($categories->count()) {
            $html .= '<div class="p-2"><strong>Categories</strong></div>';
            foreach ($categories as $category) {
                $html .= '
            <a href="' . route('category.index') . '" class="dropdown-item d-flex align-items-center">
                <i class="fas fa-tags mr-2 text-success"></i>
                <div>' . $category->name . '</div>
            </a>';
            }
        }

        if (!$items->count() && !$categories->count()) {
            $html = '<div class="p-2 text-center text-muted">Tidak ada hasil ditemukan</div>';
        }

        return response()->json(['html' => $html]);
    }
}
