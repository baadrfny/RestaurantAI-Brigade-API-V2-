<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name','tags'];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function plates()
    {
        return $this->belongsToMany(Plat::class, 'plate_ingredient', 'ingredient_id', 'plate_id');
    }
}
