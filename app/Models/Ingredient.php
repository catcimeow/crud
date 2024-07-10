<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'unit',
        'image_path',
    ];

    public function bakedGoods()
    {
        return $this->belongsToMany(BakedGood::class, 'baked_good_ingredients', 'id_ingredients', 'id_baked_goods')
            ->withPivot('qty')
            ->withTimestamps();
    }
}
