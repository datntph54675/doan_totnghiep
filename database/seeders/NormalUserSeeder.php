<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NormalUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'user',
                'fullname' => 'Normal User',
                'email' => 'user@example.com',
                'phone' => null,
            ],
            [
                'username' => 'user2',
                'fullname' => 'Second User',
                'email' => 'user2@example.com',
                'phone' => null,
            ],
        ];

        foreach ($users as $u) {
            $exists = DB::table('users')->where('username', $u['username'])->exists();
            $hashed = Hash::make('123456');

            if ($exists) {
                DB::table('users')->where('username', $u['username'])->update([
                    'password' => $hashed,
                    'fullname' => $u['fullname'],
                    'email' => $u['email'],
                    'phone' => $u['phone'],
                    'role' => 'user',
                    'status' => 'active',
                    'updated_at' => now(),
                ]);

                continue;
            }

            DB::table('users')->insert([
                'username' => $u['username'],
                'password' => $hashed,
                'fullname' => $u['fullname'],
                'email' => $u['email'],
                'phone' => $u['phone'],
                'role' => 'user',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

