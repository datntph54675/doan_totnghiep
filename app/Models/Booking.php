<?php

namespace App\Models;

use Carbon\CarbonInterface;
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
        'payment_method',
        'vnp_transaction_no',
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

    public const STATUS = [
        'upcoming' => 'Sắp khởi hành',
        'ongoing' => 'Đang diễn ra',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Huỷ',
    ];

    public const PAYMENT_STATUS = [
        'unpaid' => 'Chưa thanh toán',
        'deposit' => 'Đặt cọc',
        'paid' => 'Đã thanh toán',
        'refunded' => 'Đã hoàn tiền',
    ];

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelledByUser(): bool
    {
        if ($this->status !== 'upcoming') {
            return false;
        }

        $startDate = $this->schedule?->start_date;

        return $startDate instanceof CarbonInterface
            && $startDate->copy()->startOfDay()->greaterThanOrEqualTo(now()->startOfDay());
    }

    public function canBeRefundedByAdmin(): bool
    {
        return $this->status === 'cancelled' && $this->payment_status === 'paid';
    }
}
