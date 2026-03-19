<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run specific seeders compatible with this app's schema
        $this->call([
            AdminUserSeeder::class,
            GuideUserSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
