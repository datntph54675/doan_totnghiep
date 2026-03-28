<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours';
    protected $primaryKey = 'tour_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'policy',
        'supplier',
        'image',
        'price',
        'duration',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function departureSchedules()
    {
        return $this->hasMany(DepartureSchedule::class, 'tour_id', 'tour_id');
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class, 'tour_id', 'tour_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'tour_id', 'tour_id');
    }

    public function scopeVisibleToUsers(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where(function (Builder $subQuery) {
                $subQuery->whereNull('category_id')
                    ->orWhereHas('category', function (Builder $categoryQuery) {
                        $categoryQuery->where('status', 'active');
                    });
            });
    }
}
