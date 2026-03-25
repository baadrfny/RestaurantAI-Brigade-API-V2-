<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;

class Plat extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'image',
        'description',
        'price',
        'category_id',
        'is_available'
    ];

    public function recommendation(){
        return $this->hasMany(Recommendation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'plate_ingredient', 'plate_id', 'ingredient_id');
    }
}
