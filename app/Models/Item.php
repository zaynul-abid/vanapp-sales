<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'default_category_id',
        'default_unit_id',
        'tax_id',
        'purchase_price',
        'wholesale_price',
        'retail_price',
        'opening_stock',
        'current_stock',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'default_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'item_id');
    }
}
