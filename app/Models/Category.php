<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    protected $fillable = ['name', 'description'];

    public function tours()
    {
        return $this->hasMany(Tour::class, 'category_id', 'category_id');
    }
}