<?php

namespace App\Services;

use App\Models\Item;
use App\Models\StockUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Take stock from item
     */
    public function takeStock($itemId, $quantity, $notes = null, $userId = null)
    {
        return DB::transaction(function () use ($itemId, $quantity, $notes, $userId) {
            $item = Item::lockForUpdate()->find($itemId);

            if (!$item) {
                throw new \Exception('Item tidak ditemukan');
            }

            if ($item->stock < $quantity) {
                throw new \Exception('Stok tidak mencukupi');
            }

            // Update stock
            $item->stock -= $quantity;
            $item->save();

            // Create usage record
            $usage = StockUsage::create([
                'user_id' => $userId ?? Auth::id(),
                'item_id' => $item->id,
                'quantity' => $quantity,
                'notes' => $notes,
                'remaining_stock' => $item->stock,
                'type' => 'usage'
            ]);

            return [
                'item' => $item,
                'usage' => $usage
            ];
        });
    }

    /**
     * Check stock status
     */
    public function checkStockStatus($item)
    {
        if ($item->stock <= 0) {
            return 'out';
        }

        if ($item->stock <= $item->minimum_stock) {
            return 'critical';
        }

        return 'safe';
    }

    /**
     * Get stock statistics
     */
    public function getStatistics($userId)
    {
        return [
            'total_items' => Item::where('user_id', $userId)->count(),
            'critical_items' => Item::where('user_id', $userId)
                ->whereRaw('stock <= minimum_stock AND stock > 0')
                ->count(),
            'out_of_stock' => Item::where('user_id', $userId)
                ->where('stock', '<=', 0)
                ->count(),
            'total_usage_today' => StockUsage::where('user_id', $userId)
                ->whereDate('created_at', today())
                ->sum('quantity')
        ];
    }
}
