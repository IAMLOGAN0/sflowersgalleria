<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'pincode',
        'zip',
        'sector',
        'landmark',
        'city',
        'state',
        'country',
        'type',
        'alt_phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->name}, {$this->phone}, {$this->address}, {$this->city}, {$this->state}, {$this->country} - {$this->zip}";
    }
}
