<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'type',
        'rating',
        'content',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'rating' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
