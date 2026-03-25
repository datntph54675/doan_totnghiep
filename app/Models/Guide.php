<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Model
{
    protected $table = 'guide';
    protected $primaryKey = 'guide_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cccd',
        'language',
        'certificate',
        'experience',
        'specialization',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function departureSchedules(): HasMany
    {
        return $this->hasMany(DepartureSchedule::class, 'guide_id', 'guide_id');
    }

    public function guideAssignments(): HasMany
    {
        return $this->hasMany(GuideAssignment::class, 'guide_id', 'guide_id');
    }
}
