<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::create([
            'name' => 'Tour trong nước',
            'description' => 'Tour du lịch nội địa',
            'status' => 'active',
        ]);

        \App\Models\Category::create([
            'name' => 'Tour nước ngoài',
            'description' => 'Tour du lịch quốc tế',
            'status' => 'active',
        ]);
    }
}
