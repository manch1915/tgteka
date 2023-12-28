<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    protected $fillable = [
        'name',
        'mobile_number',
        'status'
    ];
}
