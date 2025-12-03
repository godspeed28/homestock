<?php

namespace App\Jobs;

use App\Models\StockUsage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SaveStockUsageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public $user_id,
        public $item_id,
        public $amount,
        public $description
    ) {}

    public function handle()
    {
        StockUsage::create([
            'user_id' => $this->user_id,
            'item_id' => $this->item_id,
            'amount' => $this->amount,
            'description' => $this->description,
        ]);
    }
}
