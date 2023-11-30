<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'price',
        'quantity',
        'sale',
        'old_price',
        'new_price',
        'image',
        'color',
        'size',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
