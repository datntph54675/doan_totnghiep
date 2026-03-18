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
            
            $userId = DB::table('users')->where('username', 'guide')->value('user_id');
            
            // Tạo hoặc cập nhật guide profile
            $guideExists = DB::table('guide')->where('user_id', $userId)->exists();
            if (!$guideExists) {
                DB::table('guide')->insert([
                    'user_id' => $userId,
                    'cccd' => '001234567890',
                    'language' => 'Tiếng Việt, English',
                    'certificate' => 'Chứng chỉ HDV Quốc gia',
                    'experience' => '5 năm kinh nghiệm dẫn tour',
                    'specialization' => 'Tour miền Bắc, Tour biển đảo',
                ]);
            }
            return;
        }

        $userId = DB::table('users')->insertGetId([
            'username' => 'guide',
            'password' => $hashed,
            'fullname' => 'Nguyễn Văn Hướng',
            'email' => 'guide@example.com',
            'phone' => '0912345678',
            'role' => 'tour_guide',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo guide profile
        DB::table('guide')->insert([
            'user_id' => $userId,
            'cccd' => '001234567890',
            'language' => 'Tiếng Việt, English',
            'certificate' => 'Chứng chỉ HDV Quốc gia',
            'experience' => '5 năm kinh nghiệm dẫn tour',
            'specialization' => 'Tour miền Bắc, Tour biển đảo',
        ]);
    }
}
