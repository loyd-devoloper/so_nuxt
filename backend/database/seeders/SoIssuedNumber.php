<?php

namespace Database\Seeders;

use App\Models\Qad\SoNumber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoIssuedNumber extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         SoNumber::insert([
            'id' => 1,
            'previous_issued' => 0,
            'latest_issued' => 0,
            'year' => date('Y'),
        ]);
    }
}
