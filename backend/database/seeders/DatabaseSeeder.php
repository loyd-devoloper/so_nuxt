<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::query()->create([
            "email" => 'loyd.developer@gmail.com',
            "password" => Hash::make('@loyd1234'),
            "fname" => ' loyd ',
            "lname" => 'Permato',
            "fd_code" => '01D',
            "account_status" => 'Enabled',
            "username" => 'loyd',
        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
