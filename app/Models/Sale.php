<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory , softDeletes;
    protected $fillable = [
        'bill_no',
        'sale_master_id',
        'sale_date',
        'sale_time',
        'customer_id',
        'item_id',
        'item_name',
        'rate',
        'unit_price',
        'quantity',
        'unit',
        'gross_amount',
        'tax_amount',
        'total_amount',
        'narration',
        'financial_year',
        'van_id',
        'user_id',
    ];
    public function saleMaster()
    {
        return $this->belongsTo(SaleMaster::class, 'sale_master_id', 'id');
    }
}
