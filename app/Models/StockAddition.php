<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAddition extends Model
{
    protected $fillable = ['item_id', 'quantity_added', 'note'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
