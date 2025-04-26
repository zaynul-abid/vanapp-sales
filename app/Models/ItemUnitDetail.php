<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUnitDetail extends Model
{

    protected $table = 'item_unit_details';
    protected $fillable = [
        'default_item_id',
        'name',
        'unit_name',
        'quantity',
        'type',
    ];

    public function item(){
        return $this->belongsTo(Item::class,'default_item_id');
    }
}
