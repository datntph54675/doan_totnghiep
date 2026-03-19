<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    protected $table = 'itinerary';
    protected $primaryKey = 'itinerary_id';
    public $timestamps = false;

    protected $fillable = [
        'tour_id',
        'day_number',
        'title',
        'description',
        'location',
        'time_start',
        'time_end',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'tour_id');
    }
}
