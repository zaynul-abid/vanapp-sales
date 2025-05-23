<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleMaster extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'bill_no',
        'sale_date',
        'sale_time',
        'customer_id',
        'customer_name',
        'sale_type',
        'gross_amount',
        'tax_amount',
        'total_amount',
        'discount',
        'net_gross_amount',
        'net_tax_amount',
        'net_total_amount',
        'narration',
        'cash_amount',
        'credit_amount',
        'card_amount',
        'upi_amount',
        'financial_year',
        'van_id',
        'user_id',
    ];
    public function sales()
    {
        return $this->hasMany(Sale::class, 'sale_master_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function van()
    {
        return $this->belongsTo(Van::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

