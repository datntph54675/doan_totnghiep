<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Feedback extends Model
{
    use HasFactory;

    public const TYPE_REVIEW = 'danh_gia';
    public const TYPE_INCIDENT = 'su_co';
    public const TYPE_HIDDEN_LEGACY = 'an';

    protected $table = 'feedback';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'type',
        'rating',
        'content',
        'is_hidden',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'rating' => 'integer',
        'is_hidden' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function scopeReviews(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_REVIEW);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_hidden', false);
    }

    public function scopeHidden(Builder $query): Builder
    {
        return $query->where('is_hidden', true);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_REVIEW => 'Đánh giá',
            self::TYPE_INCIDENT => 'Sự cố',
            self::TYPE_HIDDEN_LEGACY => 'Ẩn',
            default => ucfirst((string) $this->type),
        };
    }

    public function isHidden(): bool
    {
        return (bool) $this->is_hidden;
    }

    public function hide(): void
    {
        $this->update(['is_hidden' => true]);
    }

    public function unhide(): void
    {
        $this->update(['is_hidden' => false]);
    }
}
