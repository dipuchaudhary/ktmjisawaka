<?php

namespace Database\Seeders;

use App\Models\ChallaniFormat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChallaniFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChallaniFormat::create([
            'format_prefix' => '2082/083',
            'is_active' => true,
        ]);
    }
}
