<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SheetCategory extends Model
{

    // protected $table = "sheet_categories"; 
    protected $table = "category_sheet"; 
    protected $fillable = [
        'category_id', 'sheet_id'
    ];
}
