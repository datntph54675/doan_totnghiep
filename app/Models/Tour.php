<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $primaryKey = 'tour_id';
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
        'start_date',
        'end_date',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
