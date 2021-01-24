<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    protected $fillable = [
	    'type', 'title', 'body'
	];
	

    public $timestamps = true;
}
