<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tự động kiểm tra và hủy các đơn hàng đặt tour quá hạn 3 phút hàng phút
Schedule::command('app:cleanup-expired-bookings')->everyMinute();

// Tự động đồng bộ trạng thái tour và booking
Schedule::command('app:sync-tour-statuses')->everyMinute();
