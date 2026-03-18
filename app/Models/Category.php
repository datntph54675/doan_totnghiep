<?php

namespace App\Models;

 tours-admin

use Illuminate\Database\Eloquent\Factories\HasFactory;
 main
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
 tours-admin
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $fillable = ['name', 'description'];
}

    use HasFactory;

    protected $primaryKey = 'category_id';
    protected $fillable = ['name', 'description'];

    public function tours()
    {
        return $this->hasMany(Tour::class, 'category_id', 'category_id');
    }
}
 main
