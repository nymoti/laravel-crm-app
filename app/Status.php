<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    protected $fillable =[
        'name','color'
    ];

    public function sheets()
    {
        return $this->hasMany(Sheet::class);
    }
}
