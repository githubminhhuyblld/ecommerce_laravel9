<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'address',
        'image'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    
}
