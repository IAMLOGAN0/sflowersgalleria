<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'delivery_locations';

    protected $fillable = [
        'sector',
        'pin',
        'b_time',
        't_time',
    ];  
}
