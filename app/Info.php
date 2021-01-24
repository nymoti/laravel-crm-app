<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
	protected $fillable = [
        'title', 'email', 'site', 'tel', 'logo', 'time_start' ,'time_end', 'weekend'
    ];
}
