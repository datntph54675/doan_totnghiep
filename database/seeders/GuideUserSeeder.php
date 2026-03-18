<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuideUserSeeder extends Seeder
{
    public function run(): void
    {
        $exists = DB::table('users')->where('username', 'guide')->exists();
        $hashed = Hash::make('123456');
        if ($exists) {
            DB::table('users')->where('username', 'guide')->update([
                'password' => $hashed,
                'updated_at' => now(),
            ]);
            return;
        }

        DB::table('users')->insert([
            'username' => 'guide',
            'password' => $hashed,
            'fullname' => 'Guide User',
            'email' => 'guide@example.com',
            'phone' => null,
            'role' => 'tour_guide',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
