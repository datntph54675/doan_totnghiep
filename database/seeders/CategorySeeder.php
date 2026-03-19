<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $exists = DB::table('category')->where('name', 'admin')->exists();
        if ($exists) {
            return;
        }

        DB::table('category')->insert([
            'name' => 'admin',
            'description' => 'Admin category',
        ]);
    }
}
