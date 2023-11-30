<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
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
        'color',
        'size',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
