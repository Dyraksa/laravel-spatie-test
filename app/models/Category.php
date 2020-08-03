<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [ 'name','description' ];

    public function Product()
    {
        return $this->belongsToMany(Product::class,'product_categories')->withTimestamps();
    }

}

