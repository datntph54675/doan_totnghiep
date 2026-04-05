<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartureSchedule extends Model
{
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_ONGOING = 'ongoing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_LABELS = [
        self::STATUS_SCHEDULED => 'Đã lên lịch',
        self::STATUS_ONGOING => 'Đang diễn ra',
        self::STATUS_COMPLETED => 'Hoàn thành',
        self::STATUS_CANCELLED => 'Hủy',
    ];

    public const ALLOWED_TRANSITIONS = [
        self::STATUS_SCHEDULED => [self::STATUS_ONGOING, self::STATUS_CANCELLED],
        self::STATUS_ONGOING => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
        self::STATUS_COMPLETED => [],
        self::STATUS_CANCELLED => [],
    ];

    protected $table = 'departure_schedule';
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;

    protected $fillable = [
        'tour_id',
        'start_date',
        'end_date',
        'max_people',
        'available_spots',
        'meeting_point',
        'guide_id',
        'driver_id',
        'hotel_id',
        'notes',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'max_people' => 'integer',
        'available_spots' => 'integer',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'tour_id');
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class, 'guide_id', 'guide_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id', 'schedule_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'schedule_id', 'schedule_id');
    }

    public function guideAssignments(): HasMany
    {
        return $this->hasMany(GuideAssignment::class, 'schedule_id', 'schedule_id');
    }

    public function canTransitionTo(string $status): bool
    {
        if ($status === $this->status) {
            return true;
        }

        return in_array($status, self::ALLOWED_TRANSITIONS[$this->status] ?? [], true);
    }

    public function availableStatusOptions(): array
    {
        return array_values(array_unique([
            $this->status,
            ...(self::ALLOWED_TRANSITIONS[$this->status] ?? []),
        ]));
    }
}
