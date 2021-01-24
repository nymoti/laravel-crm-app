<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recall extends Model
{
    protected $fillable = [
        'full_name', 'address', 'tel', 'description', 'start_date','hour', 'color'
    ];
}
