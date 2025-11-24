<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Cari user bernama Endo
        $user = User::where('name', 'Endo')->first();

        if (!$user) {
            $this->command->warn('User Endo tidak ditemukan. Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        $categories = [
            'Kebutuhan Pokok',
            'Kebutuhan Pribadi',
            'Elektronik',
            'Kebersihan'
        ];

        foreach ($categories as $name) {
            Category::create([
                'user_id' => $user->id,
                'name' => $name,
            ]);
        }

        $this->command->info('CategorySeeder berhasil dijalankan untuk user Endo!');
    }
}
