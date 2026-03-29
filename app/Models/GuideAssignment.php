<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideAssignment extends Model
{
    protected $table = 'guide_assignment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'schedule_id',
        'guide_id',
        'assigned_by',
        'assigned_at',
        'confirmed_at',
        'status',
        'note',
        'rejection_reason',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(DepartureSchedule::class, 'schedule_id', 'schedule_id');
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class, 'guide_id', 'guide_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by', 'user_id');
    }
}
