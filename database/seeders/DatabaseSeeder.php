<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\GuideUserSeeder;
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

        // Run the admin user seeder
        $this->call(AdminUserSeeder::class);
        // Run the guide user seeder
        $this->call(GuideUserSeeder::class);
        // Run the tour data seeder
        $this->call(TourDataSeeder::class);

    }
}
