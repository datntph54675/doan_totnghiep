<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $primaryKey = 'tour_id';
    protected $table = 'tour';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'policy',
        'supplier',
        'image',
        'price',
        'max_people',
        'duration',
        'status'
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
}
