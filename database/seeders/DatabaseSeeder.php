<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'email' => 'fac.occ.gebe.barry@gmail.com',
            'password' => Hash::make('password'),

            'first_name' => 'Barry',
            'last_name' => 'Gebe',
            'middle_name' => 'Taroy',

            'phone' => '09456674590',
            'address' => 'Quezon City',

            'status' => 'active',
            'last_login_at' => now(),
        ]);
    }
}
