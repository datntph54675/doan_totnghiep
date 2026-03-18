<?php

namespace App\Models;

 tours-admin
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Tour extends Model
{
    protected $table = 'tour';
    protected $primaryKey = 'tour_id';
    public $incrementing = true;
    protected $keyType = 'int';


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $primaryKey = 'tour_id';
 main
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
 tours-admin
        'status',

        'status'
 main
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
