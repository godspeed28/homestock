<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Endo',
            'email' => 'hendro_koten@gmail.com',
            'password' => Hash::make('admin123'),
            'whatsapp_number' => null,
        ]);
    }
}
