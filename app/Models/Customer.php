<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use softDeletes;

    protected $table = 'customers';
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'address',
        'customer_type',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            // Get the highest ID from both active and soft-deleted records
            $lastCustomer = static::withTrashed()
                ->orderBy('id', 'desc')
                ->first();

            // Extract the numeric part and increment
            $nextId = $lastCustomer ? (int) substr($lastCustomer->customer_id, 5) + 1 : 1;
            $customer->customer_id = 'CUST-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });

        static::forceDeleted(function ($customer) {

        });
    }
    protected $casts = [
        'status' => 'boolean',
    ];
}
