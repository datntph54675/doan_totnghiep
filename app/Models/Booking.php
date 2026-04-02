<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    public $timestamps = true;

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
        'expires_at',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price' => 'decimal:2',
        'admin_confirmed' => 'boolean',
        'expires_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    public function hasReview(): bool
    {
        return $this->feedbacks()->where('type', Feedback::TYPE_REVIEW)->exists();
    }

    public function isFinished(): bool
    {
        if ($this->payment_status !== 'paid') {
            return false;
        }

        if ($this->status === 'completed') {
            return true;
        }

        $scheduleEnd = $this->schedule?->end_date;

        return $scheduleEnd ? ! $scheduleEnd->isFuture() : false;
    }

    public function canBeReviewed(): bool
    {
        if (! $this->isFinished()) {
            return false;
        }

        if ($this->hasReview()) {
            return false;
        }

        return true;
    }

    public function getParticipantCountAttribute(): int
    {
        return max(1, (int) $this->num_people);
    }

    public const STATUS = [
        'upcoming' => 'Sắp khởi hành',
        'ongoing' => 'Đang diễn ra',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Huỷ',
    ];

    public const PAYMENT_STATUS = [
        'unpaid' => 'Chưa thanh toán',
        // 'deposit' => 'Đặt cọc',
        'paid' => 'Đã thanh toán',
        // 'refunded' => 'Đã hoàn tiền',
    ];

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelledByUser(): bool
    {
        if ($this->status !== 'upcoming' || $this->payment_status !== 'unpaid') {
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

    public function canEditStatus(): bool
    {
        return $this->admin_confirmed;
    }
}
