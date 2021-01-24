<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{  

    protected $fillable =  [
        'created_by',
        'updated_by',
        'closer_id', 
        'client_code',
        'question_1',
        'q2_more',
        'q2_less',
        'times',
        'financials',
        'childrens',
        'distance',
        'no_oppportunity',
        'travel_agency',
        'tour_operator', 
        'only',
        'company_comittee',
        'others_q3',
        'others_desc_q3',
        'cultural_visits',
        'exercusions', 
        'sports', 
        'balneo',
        'others', 
        'others_desc',  
        'less_of_1500',
        'from_1500_to_2000', 
        'from_2000_to_3000',
        'from_3000_to_4000', 
        'or_more',
        'm_first_name',
        'm_last_name',  
        'm_profession',
        'm_age',
        'w_first_name',
        'w_last_name',
        'w_profession', 
        'w_age', 
        'married', 
        'single', 
        'divorced', 
        'widower', 
        'concubinage',
        'concubinage_since', 
        'dependent_children',
        'dc_all_ages', 
        'tel',
        'gsm',
        'email',
        'address', 
        'observations',
        'status',
        'header',
        'has_reservation',
        'nbr_pax',
        'date_departure',
        'date_arrived',
        'dd_flight_number',
        'da_flight_number',
        'establishment',
        'supplements',
        'amount',
        'comments',
        'reserved_at',
        'distributed_at',
        'active',
        'state'
    ];
    public $timestamps = true;
    
    public function updatedByUser()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function closer()
    {
        return $this->belongsTo(User::class,'closer_id');
    }
    public function agent()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function statusList()
    {
        return $this->belongsTo(Status::class,'status');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
