<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->decimal('stock', 10, 2)->default(0);
            $table->string('unit', 20);
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->boolean('notif_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
