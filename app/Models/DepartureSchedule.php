<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartureSchedule extends Model
{
    protected $table = 'departure_schedule';
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;

    protected $fillable = [
        'tour_id',
        'start_date',
        'end_date',
        'max_people',
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
}
