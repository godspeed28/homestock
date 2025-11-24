<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            // Hapus semua item yang terkait
            $category->items()->delete();
        });
    }
}
