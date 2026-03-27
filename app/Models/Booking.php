<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    public $timestamps = false;

    protected $fillable = [
        'schedule_id',
        'customer_id',
        'tour_id',
        'user_id',
        'booking_date',
        'num_people',
        'total_price',
        'status',
        'admin_confirmed',
        'payment_status',
        'note',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price' => 'decimal:2',
        'admin_confirmed' => 'boolean',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'tour_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(DepartureSchedule::class, 'schedule_id', 'schedule_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'booking_id', 'booking_id');
    }
}
