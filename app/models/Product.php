<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'status',
    ];

    public function photos()
    {
        return $this->morphMany(Product::class, 'imageable');
    }

    public function Category()
    {
        return $this->belongsToMany(Category::class,'product_categories')->withTimestamps();
    }
}
