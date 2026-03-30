<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideFeedback extends Model
{
    protected $table = 'guide_feedback';
    protected $primaryKey = 'id';

    protected $fillable = [
        'guide_id',
        'type',
        'title',
        'content',
        'status',
        'admin_reply',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the guide that submitted this feedback
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class, 'guide_id', 'guide_id');
    }

    /**
     * Get status badge label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Chưa xem',
            'viewed' => 'Đã xem',
            'resolved' => 'Đã xử lý',
            default => $this->status,
        };
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'danh_gia' => 'Đánh giá hệ thống',
            'su_co' => 'Báo cáo sự cố',
            default => $this->type,
        };
    }

    /**
     * Scope: Get pending feedbacks
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get feedback by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
