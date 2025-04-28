<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Van extends Model
{
    use HasFactory,softDeletes;

    protected $fillable = [
        'name',
        'register_number',
        'status',
        'model',
        'manufacture_year',
        'capacity',
        'employee_id'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function setRegisterNumberAttribute($value)
    {
        $this->attributes['register_number'] = strtoupper($value);
    }


    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
