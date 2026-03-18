<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $exists = DB::table('users')->where('username', 'admin')->exists();
        $hashed = Hash::make('123456');
        if ($exists) {
            DB::table('users')->where('username', 'admin')->update([
                'password' => $hashed,
                'updated_at' => now(),
            ]);
            return;
        }

        DB::table('users')->insert([
            'username' => 'admin',
            'password' => $hashed,
            'fullname' => 'Administrator',
            'email' => 'admin@example.com',
            'phone' => null,
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
