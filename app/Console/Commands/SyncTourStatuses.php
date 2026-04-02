<?php

namespace App\Console\Commands;

use App\Services\TourAvailabilityService;
use Illuminate\Console\Command;

class SyncTourStatuses extends Command
{
    protected $signature = 'app:sync-tour-statuses';

    protected $description = 'Đồng bộ trạng thái booking và tour theo dữ liệu hiện tại';

    public function handle(TourAvailabilityService $tourAvailabilityService): int
    {
        $tourAvailabilityService->sync();

        $this->info('Đã đồng bộ trạng thái tour và booking.');

        return self::SUCCESS;
    }
}
