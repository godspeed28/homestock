<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'stock',
        'unit',
        'harga_satuan',
        'minimum_stock',
        'user_id'
    ];

    // Relationship dengan category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship dengan usages (stock_usage)
    public function usages()
    {
        return $this->hasMany(StockUsage::class);
    }

    // Relationship dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk total harga
    public function getTotalHargaAttribute()
    {
        return $this->stock * $this->harga_satuan;
    }

    // Scope untuk user saat ini
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', Auth::id());
    }
}
