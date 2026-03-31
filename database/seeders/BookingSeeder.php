<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $tours     = DB::table('tours')->pluck('tour_id')->toArray();
        $customers = DB::table('customer')->pluck('customer_id')->toArray();
        $schedules = DB::table('departure_schedule')->get(['schedule_id', 'tour_id'])->toArray();
        $userId    = DB::table('users')->where('role', 'admin')->value('user_id')
                     ?? DB::table('users')->value('user_id');

        $paymentMethods  = ['vnpay', 'momo', 'vietqr'];
        $paymentStatuses = ['paid', 'paid', 'paid', 'deposit', 'refunded']; // lebih banyak paid
        $bookingStatuses = ['upcoming', 'ongoing', 'completed', 'cancelled'];

        $bookings = [];

        foreach ($schedules as $i => $schedule) {
            $numPeople     = rand(1, 5);
            $tourPrice     = DB::table('tours')->where('tour_id', $schedule->tour_id)->value('price') ?? 1500000;
            $totalPrice    = $tourPrice * $numPeople;
            $payStatus     = $paymentStatuses[array_rand($paymentStatuses)];
            $bookingStatus = $payStatus === 'refunded' ? 'cancelled'
                           : $bookingStatuses[array_rand(['upcoming', 'ongoing', 'completed'])];
            $method        = $paymentMethods[array_rand($paymentMethods)];
            $daysAgo       = rand(1, 60);

            $bookings[] = [
                'customer_id'       => $customers[array_rand($customers)],
                'tour_id'           => $schedule->tour_id,
                'schedule_id'       => $schedule->schedule_id,
                'user_id'           => $userId,
                'booking_date'      => Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s'),
                'num_people'        => $numPeople,
                'total_price'       => $totalPrice,
                'status'            => $bookingStatus,
                'payment_status'    => $payStatus,
                'payment_method'    => $method,
                'admin_confirmed'   => $payStatus === 'paid' ? (bool) rand(0, 1) : false,
                'vnp_transaction_no'=> $method !== 'vietqr' ? strtoupper(substr(md5(uniqid()), 0, 12)) : null,
                'note'              => null,
                'expires_at'        => null,
                'created_at'        => Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s'),
                'updated_at'        => Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s'),
            ];
        }

        // Thêm thêm 10 booking nữa để đủ dữ liệu
        for ($i = 0; $i < 10; $i++) {
            $schedule   = $schedules[array_rand($schedules)];
            $numPeople  = rand(1, 4);
            $tourPrice  = DB::table('tours')->where('tour_id', $schedule->tour_id)->value('price') ?? 2000000;
            $payStatus  = 'paid';
            $daysAgo    = rand(1, 30);

            $bookings[] = [
                'customer_id'       => $customers[array_rand($customers)],
                'tour_id'           => $schedule->tour_id,
                'schedule_id'       => $schedule->schedule_id,
                'user_id'           => $userId,
                'booking_date'      => Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s'),
                'num_people'        => $numPeople,
                'total_price'       => $tourPrice * $numPeople,
                'status'            => 'completed',
                'payment_status'    => $payStatus,
                'payment_method'    => $paymentMethods[array_rand($paymentMethods)],
                'admin_confirmed'   => true,
                'vnp_transaction_no'=> strtoupper(substr(md5(uniqid()), 0, 12)),
                'note'              => null,
                'expires_at'        => null,
                'created_at'        => Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s'),
                'updated_at'        => Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('booking')->insert($bookings);

        $this->command->info('Đã tạo ' . count($bookings) . ' booking mẫu.');
    }
}
