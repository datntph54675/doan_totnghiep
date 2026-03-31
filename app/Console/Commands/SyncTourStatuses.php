<?php

namespace App\Console\Commands;

use App\Services\TourAvailabilityService;
use Illuminate\Console\Command;

class SyncTourStatuses extends Command
{
    protected $signature = 'app:sync-tour-statuses';

    protected $description = 'Đồng bộ trạng thái lịch khởi hành, booking và tour theo thời gian thực';

    public function handle(TourAvailabilityService $tourAvailabilityService): int
    {
        $tourAvailabilityService->sync();

        $this->info('Đã đồng bộ trạng thái tour, lịch khởi hành và booking.');

        return self::SUCCESS;
    }
}
