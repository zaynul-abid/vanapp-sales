<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory,softDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'position', 'department_id', 'status'
    ];

    protected $hidden = ['password'];


    protected $casts =[
        'status' => 'boolean',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function van()
    {
        return $this->hasOne(Van::class);
    }



}
