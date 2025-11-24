<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Cari user Endo
        $user = User::where('name', 'Endo')->first();

        if (!$user) {
            $this->command->warn('User Endo tidak ditemukan. Jalankan UserSeeder dulu.');
            return;
        }

        // Ambil kategori milik user, mapping nama → ID
        $categories = Category::where('user_id', $user->id)->pluck('id', 'name');

        // Item sesuai kategori
        $items = [
            [
                'name' => 'Beras Premium',
                'unit' => 'kg',
                'stock' => 25,
                'minimum_stock' => 10,
                'harga_satuan' => 14000,
                'category_id' => $categories['Kebutuhan Pokok'] ?? null,
            ],
            [
                'name' => 'Minyak Goreng',
                'unit' => 'liter',
                'stock' => 15,
                'minimum_stock' => 5,
                'harga_satuan' => 16000,
                'category_id' => $categories['Kebutuhan Pokok'] ?? null,
            ],
            [
                'name' => 'Sabun Mandi',
                'unit' => 'pcs',
                'stock' => 30,
                'minimum_stock' => 10,
                'harga_satuan' => 5000,
                'category_id' => $categories['Kebutuhan Pribadi'] ?? null,
            ],
            [
                'name' => 'Sampo Botol',
                'unit' => 'pcs',
                'stock' => 20,
                'minimum_stock' => 5,
                'harga_satuan' => 12000,
                'category_id' => $categories['Kebutuhan Pribadi'] ?? null,
            ],
            [
                'name' => 'Lampu LED 12W',
                'unit' => 'pcs',
                'stock' => 40,
                'minimum_stock' => 10,
                'harga_satuan' => 15000,
                'category_id' => $categories['Elektronik'] ?? null,
            ],
            [
                'name' => 'Kabel Roll 10 Meter',
                'unit' => 'pcs',
                'stock' => 10,
                'minimum_stock' => 2,
                'harga_satuan' => 35000,
                'category_id' => $categories['Elektronik'] ?? null,
            ],
            [
                'name' => 'Sabun Cuci Piring',
                'unit' => 'pcs',
                'stock' => 25,
                'minimum_stock' => 8,
                'harga_satuan' => 4000,
                'category_id' => $categories['Kebersihan'] ?? null,
            ],
            [
                'name' => 'Pewangi Lantai',
                'unit' => 'liter',
                'stock' => 12,
                'minimum_stock' => 3,
                'harga_satuan' => 9000,
                'category_id' => $categories['Kebersihan'] ?? null,
            ],
        ];

        foreach ($items as $data) {
            Item::create([
                'user_id'        => $user->id,
                'category_id'    => $data['category_id'],
                'name'           => $data['name'],
                'unit'           => $data['unit'],
                'stock'          => $data['stock'],
                'minimum_stock'  => $data['minimum_stock'],
                'harga_satuan'   => $data['harga_satuan'],
                'total_harga'    => $data['harga_satuan'] * $data['stock'], // otomatis
                'notif_enabled'  => true,
            ]);
        }

        $this->command->info('ItemSeeder berhasil dijalankan dengan harga_satuan dan total_harga!');
    }
}
