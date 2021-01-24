<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Sheet;
use App\User;
use App\Header;
use App\Status;
use App\Category;
use App\SheetCategory;
use Auth; 
use Carbon\Carbon;
use Toastr;
use DB;
use PDF;
use Exception;
use App\Mail\SendEmailToClient;
use DataTables;

class SheetController extends Controller {
    private $order = 0; 


    public function __construct() {
        // $this->middleware(['auth', 'clearance'])->except('index', 'show');
        parent::__construct();
        $this->middleware('permission:sheet-list');
        $this->middleware('permission:sheet-create', ['only' => ['create','store']]);
        $this->middleware('permission:sheet-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:sheet-delete', ['only' => ['destroy']]);
        $this->middleware('permission:send-email', ['only' => ['sendEmail']]);
        $this->middleware('permission:sheet-list-by-agent', ['only' => ['getSheetsByAgentPage']]);
    }
    
    public function index(){
        $agent_id = Auth()->user()->id; 
        $sheets_side_bar = "sheets side bar"; 
        $sheet_side_bar_list = "sheet_side_bar_list"; 
        $sheets = Sheet::where('created_by', $agent_id)->where('active', 1)->whereDate('created_at', Carbon::today())->get();
        
        $count_sheets = count($sheets);
        return view('app.sheets.index')
        ->with('sheets', $sheets)
        ->with('count_sheets', $count_sheets)
        ->with('sheets_side_bar', $sheets_side_bar)
        ->with('sheet_side_bar_list', $sheet_side_bar_list);
    }   

    public function getAgentsSheets(){
        $agent_id = Auth()->user()->id; 
        $sheets_side_bar = "sheets side bar"; 
        $sheet_side_bar_list = "sheet_side_bar_list"; 
        $sheets = Sheet::where('created_by', $agent_id)->where('active', 1)->whereDate('created_at', Carbon::today())->get();
       
        $count_sheets = count($sheets);
        return view('app.sheets.agentsheets') 
        ->with('count_sheets', $count_sheets)
        ->with('sheets_side_bar', $sheets_side_bar)
        ->with('sheet_side_bar_list', $sheet_side_bar_list);
    }

    public function getAgentsSheetsData()
    { 
        $created_by = Auth()->user()->id; 
        $sheets = DB::table('sheets')
                ->join('users', 'users.id', '=', 'sheets.created_by') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.created_by', $created_by) 
                ->whereDate('sheets.created_at', Carbon::today())
                ->where('sheets.active', 1) 
                ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))
                ->orderBy('sheets.id', 'asc')
                ->get(); 
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifierx">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';                   

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                })   
                ->rawColumns([ 'order', 'client_full_name', 'action', 'created_at' ])   
                ->make(true);
    }     
     
    public function getNRPSheets()
    {      
        $scripts_filter_side_bar = "scripts_filter_side_bar"; 
        $scripts_filter_nrp = "scripts_filter_nrp";  
        $status_name = 'NRP';
        $status_id = 1;
        return view('app.sheets.bystatus') 
        ->with('status_name', $status_name) 
        ->with('status_id', $status_id) 
        ->with('scripts_filter_side_bar', $scripts_filter_side_bar)
        ->with('scripts_filter_nrp', $scripts_filter_nrp);
    }

    public function getRefuSheets(){  
        $scripts_filter_side_bar = "scripts_filter_side_bar"; 
        $scripts_filter_refu = "scripts_filter_refu";  
        $status_name = 'REFUS';
        $status_id = 2;
        return view('app.sheets.bystatus') 
        ->with('status_name', $status_name) 
        ->with('status_id', $status_id)  
        ->with('scripts_filter_side_bar', $scripts_filter_side_bar)
        ->with('scripts_filter_refu', $scripts_filter_refu);
    }
    public function getRappelSheets(){ 
        $scripts_filter_side_bar = "scripts_filter_side_bar"; 
        $scripts_filter_rappel = "scripts_filter_rappel";  
        $status_name = 'RAPPEL';
        $status_id = 3;
        return view('app.sheets.bystatus') 
        ->with('status_name', $status_name) 
        ->with('status_id', $status_id) 
        ->with('scripts_filter_side_bar', $scripts_filter_side_bar)
        ->with('scripts_filter_rappel', $scripts_filter_rappel);
    }

    public function getCloserSheetsByStatus($status_id)
    { 
        $user_id = Auth()->user()->id;
        $sheets = DB::table('sheets') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.closer_id', $user_id) 
                ->where('sheets.status', $status_id) 
                ->where('sheets.active', 1) 
                ->select( 'sheets.*','status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();   
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifierx">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';                      
                    if(Auth()->user()->can('send-email')){
                        if($sheet->email != ''){
                            $show .= '<a id="openSendEmailToClient" ' ;
                            $show .= ' value="'. $sheet->id .'" ' ; 
                            $show .= ' name="'.  $this->order  .'" ' ;  
                            $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                            $show .=  ' data-title="Envoie d\'email">'; 
                            $show .=  ' <span class="ti-email"></span>';     
                            $show .=  ' </a>';  
                        }   
                    }                 

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at', 'distributed_at'])   
                ->make(true);
    }
 
    
    public function create()
    { 
        $sheets_side_bar = "sheets side bar"; 
        $sheets_side_bar_newone = "sheets side bar new one";         
        $header = Header::where('set_default',1)->first();       
        //  return view('app.sheets.create')
       return view('app.sheets.create')
        ->with('sheets_side_bar', $sheets_side_bar)
        ->with('header', $header)
        ->with('sheets_side_bar_newone', $sheets_side_bar_newone); 

    }
 
    public function store(Request $request)
    {  
        $this->validate($request, [ 
            'tel' => 'required' 
        ]); 
        // dd($request->all());
        $sheet = new Sheet;
        $sheet->created_by = $request->created_by ;
        $sheet->updated_by = 0 ; 
        $sheet->closer_id = 0 ; 
        $sheet->client_code = $request->client_code ;
        $request->has('question_1') ? $sheet->question_1 = $request->question_1 : $sheet->question_1 = 0;
        if($request->has('question_2')){
            if($request->question_2 != 0 ){
                $sheet->q2_more =  1 ;
                $sheet->q2_less = 0 ;
            }else{
                $sheet->q2_more =  0 ;
                $sheet->q2_less = 1 ;
            }
            
        }else{
            $sheet->q2_more =  0 ;
            $sheet->q2_less = 0 ;
        } 
        $request->has('times') ? $sheet->times = $request->times : $sheet->times = 0;
        $request->has('financials') ? $sheet->financials = $request->financials : $sheet->financials = 0;
        $request->has('childrens') ? $sheet->childrens = $request->childrens : $sheet->childrens = 0;
        $request->has('distance') ? $sheet->distance = $request->distance : $sheet->distance = 0;
        $request->has('no_oppportunity') ? $sheet->no_oppportunity = $request->no_oppportunity : $sheet->no_oppportunity = 0;
        $request->has('travel_agency') ? $sheet->travel_agency = $request->travel_agency : $sheet->travel_agency = 0;
        $request->has('tour_operator') ? $sheet->tour_operator = $request->tour_operator : $sheet->tour_operator = 0;
        $request->has('only') ? $sheet->only = $request->only : $sheet->only = 0;
        $request->has('company_comittee') ? $sheet->company_comittee = $request->company_comittee : $sheet->company_comittee = 0;
        $request->has('others_q3') ? $sheet->others_q3 = $request->others_q3 : $sheet->others_q3 = 0;
        $request->has('others_desc_q3') ? $sheet->others_desc_q3 = $request->others_desc_q3 : $sheet->others_desc_q3 = 0;
        $request->has('cultural_visits') ? $sheet->cultural_visits = $request->cultural_visits : $sheet->cultural_visits = 0;
        $request->has('exercusions') ? $sheet->exercusions = $request->exercusions : $sheet->exercusions = 0;
        $request->has('sports') ? $sheet->sports = $request->sports : $sheet->sports = 0;
        $request->has('balneo') ? $sheet->balneo = $request->balneo : $sheet->balneo = 0;
        $request->has('others') ? $sheet->others = $request->others : $sheet->others = 0;
        $request->has('others_desc') ? $sheet->others_desc = $request->others_desc : $sheet->others_desc = 0;
        $request->has('less_of_1500') ? $sheet->less_of_1500 = $request->less_of_1500 : $sheet->less_of_1500 = 0;
        $request->has('from_1500_to_2000') ? $sheet->from_1500_to_2000 = $request->from_1500_to_2000 : $sheet->from_1500_to_2000 = 0;
        $request->has('from_2000_to_3000') ? $sheet->from_2000_to_3000 = $request->from_2000_to_3000 : $sheet->from_2000_to_3000 = 0;
        $request->has('from_3000_to_4000') ? $sheet->from_3000_to_4000 = $request->from_3000_to_4000 : $sheet->from_3000_to_4000 = 0;
        $request->has('or_more') ? $sheet->or_more = $request->or_more : $sheet->or_more = 0;        
        $sheet->m_first_name = $request->m_first_name ;
        $sheet->m_last_name = $request->m_last_name ;
        $sheet->m_profession = $request->m_profession ;
        $sheet->m_age = $request->m_age ;
        $sheet->w_first_name = $request->w_first_name ;
        $sheet->w_last_name = $request->w_last_name ;
        $sheet->w_profession = $request->w_profession ;
        $sheet->w_age  = $request->w_age ;
        $request->has('married') ? $sheet->married = $request->married : $sheet->married = 0;
        $request->has('single') ? $sheet->single = $request->single : $sheet->single = 0;
        $request->has('divorced') ? $sheet->divorced = $request->divorced : $sheet->divorced = 0;
        $request->has('widower') ? $sheet->widower = $request->widower : $sheet->widower = 0;
        $request->has('concubinage') ? $sheet->concubinage = $request->concubinage : $sheet->concubinage = 0;       
        $sheet->concubinage_since  = $request->concubinage_since ;
        $sheet->dependent_children  = $request->dependent_children ; 
        $sheet->dc_all_ages = '';
        if($request->has('dc_age1')){
            if($request->dc_age1 != ''){
                $sheet->dc_all_ages.= $request->dc_age1 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age2')){
            if($request->dc_age2 != ''){
                $sheet->dc_all_ages.= $request->dc_age2 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age3')){
            if($request->dc_age3 != ''){
                $sheet->dc_all_ages .= $request->dc_age3 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age4')){
            if($request->dc_age4 != ''){
                $sheet->dc_all_ages.= $request->dc_age4 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age5')){
            if($request->dc_age5 != ''){
                $sheet->dc_all_ages.= $request->dc_age5 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age6')){
            if($request->dc_age6 != ''){
                $sheet->dc_all_ages.= $request->dc_age6 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age7')){
            if($request->dc_age7 != ''){
                $sheet->dc_all_ages.= $request->dc_age7 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age8')){
            if($request->dc_age8 != ''){
                $sheet->dc_all_ages.= $request->dc_age8 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age9')){
            if($request->dc_age9 != ''){
                $sheet->dc_all_ages.= $request->dc_age9 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age10')){
            if($request->dc_age10 != ''){
                $sheet->dc_all_ages.= $request->dc_age10 ;
            }else{
                $sheet->dc_all_ages.= 0 ;
            }
        }else{
            $sheet->dc_all_ages.= 0 ;
        }
        // dd( $sheet->dc_all_ages);
        // $request->has('dc_age1') ? $sheet->dc_age1 = $request->dc_age1 : $sheet->dc_age1 = 0; 
        // $request->has('dc_age2') ? $sheet->dc_age2 = $request->dc_age2 : $sheet->dc_age2 = 0; 
        // $request->has('dc_age3') ? $sheet->dc_age3 = $request->dc_age3 : $sheet->dc_age3 = 0; 
        // $request->has('dc_age4') ? $sheet->dc_age4 = $request->dc_age4 : $sheet->dc_age4 = 0; 
        // $request->has('dc_age5') ? $sheet->dc_age5 = $request->dc_age5 : $sheet->dc_age5 = 0; 
        // $request->has('dc_age6') ? $sheet->dc_age6 = $request->dc_age6 : $sheet->dc_age6 = 0; 
        // $request->has('dc_age7') ? $sheet->dc_age7 = $request->dc_age7 : $sheet->dc_age7 = 0; 
        // $request->has('dc_age8') ? $sheet->dc_age8 = $request->dc_age8 : $sheet->dc_age8 = 0; 
        // $request->has('dc_age9') ? $sheet->dc_age9 = $request->dc_age9 : $sheet->dc_age9 = 0; 
        // $request->has('dc_age10') ? $sheet->dc_age10 = $request->dc_age10 : $sheet->dc_age10 = 0; 
        $sheet->tel  = $request->tel ;
        $sheet->gsm  = $request->gsm ;
        $sheet->email  = $request->email ;
        $sheet->address  = $request->address ;
        $sheet->observations  = $request->observations ; 
        $sheet->status = 0 ;
        $sheet->header = $request->headerId; 
        $sheet->has_reservation = 0;
        $sheet->active = 1 ;
        $sheet->state = 0 ;
        $sheet->save();        
        Toastr::success('Votre fiche a été ajoutée avec succès', 'Ajouter une fiche', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getAgentsSheets');  

    }
    public function show($id)
    {  
        $sheet = Sheet::findOrFail($id); 
        $status = Status::all();
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;
        $sheets_side_bar = "sheets_side_bar";  

        $dc_all_agesArray = $sheet->dc_all_ages;                 
        $dc_all_agesArray = explode(',', $dc_all_agesArray);
        $dc_all_ages = [];
         
        foreach ($dc_all_agesArray as $key => $age) {
            array_push($dc_all_ages,  $age  );
            // dd($sheet->dependent_children);
            if($key+1 === $sheet->dependent_children){
                 break;
            }
        }
        // dd($dc_all_ages);
        return view('app.sheets.show')
        ->with('sheet', $sheet)
        ->with('dc_all_ages', $dc_all_ages)
        ->with('status', $status) 
        ->with('header', $header) 
        ->with('sheets_side_bar', $sheets_side_bar); 
    }

    public function edit($id)
    {    
        $sheet = Sheet::findOrFail($id);
        $status = Status::all();
        $sheets_side_bar = "sheets_side_bar";
        $updated_by = $sheet->updated_by ; 
        $logedUser = Auth()->user(); 
        $last_update_by = 'aucun';
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;


        $dc_all_agesArray = $sheet->dc_all_ages;                 
        $dc_all_agesArray = explode(',', $dc_all_agesArray);
        $dc_all_ages = [];
         
        foreach ($dc_all_agesArray as $key => $age) {
            array_push($dc_all_ages,  $age  );
            // dd($sheet->dependent_children);
            if($key+1 === $sheet->dependent_children){
                 break;
            }
        }

        if($logedUser->hasRole('Admin|Closer')){
            if($updated_by != 0){
                $user = User::findOrFail($updated_by);
                $last_update_by = $user->first_name . ' ' . $user->last_name ;
            }                
        }else{            
            $last_update_by = 'aucun';
        } 

        // dd($dc_all_ages);
        return view('app.sheets.editform')
        ->with('sheet', $sheet)
        ->with('dc_all_ages', $dc_all_ages)
        ->with('status', $status)
        ->with('header', $header)
        ->with('last_update_by', $last_update_by)
        ->with('sheets_side_bar', $sheets_side_bar); 
    } 

    public function update(Request $request, $id)
    {  
        $user = Auth()->user();
        $sheet = Sheet::findOrFail($id);
        $this->validate($request, [ 
            'tel' => 'required'
        ]); 
          
        $sheet->updated_by = $user->id ; 
        $sheet->client_code = $request->client_code ;  
        // if($user->hasRole('Closer')){
        //     $sheet->closer_id = $user->id;
        // } 
        $sheet->client_code = $request->client_code ;
        $sheet->question_1 = $request->question_1 ;
        if($request->has('question_2')){
            if($request->question_2 != 0 ){
                $sheet->q2_more =  1 ;
                $sheet->q2_less = 0 ;
            }else{
                $sheet->q2_more =  0 ;
                $sheet->q2_less = 1 ;
            }
            
        }else{
            $sheet->q2_more =  0 ;
            $sheet->q2_less = 0 ;
        } 
        $request->has('times') ? $sheet->times = $request->times : $sheet->times = 0;
        $request->has('financials') ? $sheet->financials = $request->financials : $sheet->financials = 0;
        $request->has('childrens') ? $sheet->childrens = $request->childrens : $sheet->childrens = 0;
        $request->has('distance') ? $sheet->distance = $request->distance : $sheet->distance = 0;
        $request->has('no_oppportunity') ? $sheet->no_oppportunity = $request->no_oppportunity : $sheet->no_oppportunity = 0;
        $request->has('others_q3') ? $sheet->others_q3 = $request->others_q3 : $sheet->others_q3 = 0;
        $request->has('others_desc_q3') ? $sheet->others_desc_q3 = $request->others_desc_q3 : $sheet->others_desc_q3 = 0;
        $request->has('travel_agency') ? $sheet->travel_agency = $request->travel_agency : $sheet->travel_agency = 0;
        $request->has('tour_operator') ? $sheet->tour_operator = $request->tour_operator : $sheet->tour_operator = 0;
        $request->has('only') ? $sheet->only = $request->only : $sheet->only = 0;
        $request->has('company_comittee') ? $sheet->company_comittee = $request->company_comittee : $sheet->company_comittee = 0;
        $request->has('cultural_visits') ? $sheet->cultural_visits = $request->cultural_visits : $sheet->cultural_visits = 0;
        $request->has('exercusions') ? $sheet->exercusions = $request->exercusions : $sheet->exercusions = 0;
        $request->has('sports') ? $sheet->sports = $request->sports : $sheet->sports = 0;
        $request->has('balneo') ? $sheet->balneo = $request->balneo : $sheet->balneo = 0;
        $request->has('others') ? $sheet->others = $request->others : $sheet->others = 0;
        $request->has('others_desc') ? $sheet->others_desc = $request->others_desc : $sheet->others_desc = 0;
        $request->has('less_of_1500') ? $sheet->less_of_1500 = $request->less_of_1500 : $sheet->less_of_1500 = 0;
        $request->has('from_1500_to_2000') ? $sheet->from_1500_to_2000 = $request->from_1500_to_2000 : $sheet->from_1500_to_2000 = 0;
        $request->has('from_2000_to_3000') ? $sheet->from_2000_to_3000 = $request->from_2000_to_3000 : $sheet->from_2000_to_3000 = 0;
        $request->has('from_3000_to_4000') ? $sheet->from_3000_to_4000 = $request->from_3000_to_4000 : $sheet->from_3000_to_4000 = 0;
        $request->has('or_more') ? $sheet->or_more = $request->or_more : $sheet->or_more = 0;        
        $sheet->m_first_name = $request->m_first_name ;
        $sheet->m_last_name = $request->m_last_name ;
        $sheet->m_profession = $request->m_profession ;
        $sheet->m_age = $request->m_age ;
        $sheet->w_first_name = $request->w_first_name ;
        $sheet->w_last_name = $request->w_last_name ;
        $sheet->w_profession = $request->w_profession ;
        $sheet->w_age  = $request->w_age ;
        $request->has('married') ? $sheet->married = $request->married : $sheet->married = 0;
        $request->has('single') ? $sheet->single = $request->single : $sheet->single = 0;
        $request->has('divorced') ? $sheet->divorced = $request->divorced : $sheet->divorced = 0;
        $request->has('widower') ? $sheet->widower = $request->widower : $sheet->widower = 0;
        $request->has('concubinage') ? $sheet->concubinage = $request->concubinage : $sheet->concubinage = 0;       
        $sheet->concubinage_since  = $request->concubinage_since ;
        if($request->has('dependent_children')){
            if($request->dependent_children != 0){
                $sheet->dependent_children  = $request->dependent_children ;
            }else{
                $sheet->dependent_children  = 1 ;
            }
        }
        $sheet->dc_all_ages = '';
        if($request->has('dc_age1')){
            if($request->dc_age1 != ''){
                $sheet->dc_all_ages.= $request->dc_age1 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age2')){
            if($request->dc_age2 != ''){
                $sheet->dc_all_ages.= $request->dc_age2 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age3')){
            if($request->dc_age3 != ''){
                $sheet->dc_all_ages .= $request->dc_age3 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age4')){
            if($request->dc_age4 != ''){
                $sheet->dc_all_ages.= $request->dc_age4 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age5')){
            if($request->dc_age5 != ''){
                $sheet->dc_all_ages.= $request->dc_age5 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age6')){
            if($request->dc_age6 != ''){
                $sheet->dc_all_ages.= $request->dc_age6 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age7')){
            if($request->dc_age7 != ''){
                $sheet->dc_all_ages.= $request->dc_age7 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age8')){
            if($request->dc_age8 != ''){
                $sheet->dc_all_ages.= $request->dc_age8 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age9')){
            if($request->dc_age9 != ''){
                $sheet->dc_all_ages.= $request->dc_age9 .',';
            }else{
                $sheet->dc_all_ages.= 0 .',';
            }
        }else{
            $sheet->dc_all_ages.= 0 .',';
        }
        if($request->has('dc_age10')){
            if($request->dc_age10 != ''){
                $sheet->dc_all_ages.= $request->dc_age10 ;
            }else{
                $sheet->dc_all_ages.= 0 ;
            }
        }else{
            $sheet->dc_all_ages.= 0 ;
        }
        $sheet->tel  = $request->tel ;
        $sheet->gsm  = $request->gsm ;
        $sheet->email  = $request->email ;
        $sheet->address  = $request->address ;
        $sheet->observations  = $request->observations ;
        $request->has('status') ? $sheet->status = $request->status : $sheet->status = 0;


        if($request->has('state')){
            if($request->state == 1){ 
                $sheet->state = 1 ;
            }else if($request->state == 2) { 
                $sheet->state = 2 ;
            }else{ 
                $sheet->state = 0 ;
            } 
        } 


        $sheet->update();         
        Toastr::success('Votre Fiche a été modifié avec succès', 'Modifier une fiche', ["positionClass" => "toast-top-right"]);
        
        if($user->hasRole('Admin')){ 
            return redirect()->back();
        }else if ($user->hasRole('Closer')){ 
            return redirect()->route('getCloserSheets');
        } else if($user->hasRole('Super Admin')) { 
            return redirect()->back();      
        }else{ 
            return redirect()->route('getAgentsSheets');
        }
         

    } 
    public function destroy(Request $request)
    {
        // dd($request->all());
        $sheet = Sheet::findOrFail($request->sheet_id);
        // $sheet->delete();
        $sheet->active = 0;
        $sheet->update();
        Toastr::success('Fiche a été supprimée avec succès', 'Supprimer une Fiche', ["positionClass" => "toast-top-right"]);
        //return redirect()->route('sheets.index');
        return redirect()->back(); 
    }

     
    public function getUnDistributedSheets()//except distributed sheet
    {          
        $undistributed_sheets_side_bar = "undistributed_sheets_side_bar";  
        $users_closer = User::role('Closer')->get();   
        $created_at ='';   
        return view('app.sheets.undistributedsheets')    
        ->with('created_at', $created_at) 
        ->with('users_closer', $users_closer)  
        ->with('undistributed_sheets_side_bar', $undistributed_sheets_side_bar);
    }

    public function getUnDistributedSheetsData( $created_at = null )//except distributed sheet
    {  
        $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.closer_id', 0) 
                    ->where('sheets.active', 1) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Distribuer">'; 
                    $show .=  ' <span class="ti-wand"></span>';     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }                    

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status','multi_selection', 'action', 'created_at'])   
                ->make(true);           
    }

    public function getUnDistributedSheetsFiltePage(Request $request)
    {
        $undistributed_sheets_side_bar = "undistributed_sheets_side_bar";  
        $users_closer = User::role('Closer')->get();  
        $created_at = '' ;  
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = '';
        } 
        return view('app.sheets.undistributedsheets')
        ->with('undistributed_sheets_side_bar',$undistributed_sheets_side_bar) 
        ->with('users_closer',$users_closer)  
        ->with('created_at',$created_at);
    }
    
    public function getUnDistributedSheetsFilterData( $created_at ) 
    { 
        $created_at = date("Y-m-d", strtotime($created_at));
        if($created_at != '' ){
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.created_by') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->whereDate('sheets.created_at', $created_at) 
                        ->where('sheets.closer_id', 0) 
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
        } else {
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.created_by') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->where('sheets.active', 1)  
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            
        }  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Distribuer">'; 
                    $show .=  ' <span class="ti-wand"></span>';     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }                    

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status','multi_selection', 'action', 'created_at'])   
                ->make(true);           
    }
 

    public function getDistributedSheets()//except distributed sheet
    {          
        $distributed_sheets_side_bar = "distributed_sheets_side_bar"; 
        $users_closer = User::role('Closer')->get();    
        $created_at =''; 
        $date_type = '';
        return view('app.sheets.distributedsheets')    
        ->with('date_type', $date_type) 
        ->with('created_at', $created_at) 
        ->with('users_closer', $users_closer)  
        ->with('distributed_sheets_side_bar', $distributed_sheets_side_bar);
    }

    public function getDistributedSheetsData()//except distributed sheet
    {  
        $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.closer_id') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.closer_id','!=', 0) 
                    ->where('sheets.active', 1) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    if( $sheet->status != 0 ){
                        $status .=  $sheet->name  ;
                    }else{
                        $status .=  'Aucun status'  ;                        
                    } 
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="redistribuer">'; 
                    $show .=  ' <span class="ti-settings"></span>';     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }                    

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status','multi_selection', 'action', 'created_at', 'distributed_at'])   
                ->make(true);           
    }

    public function getDistributedSheetsFilterPage(Request $request)
    { 
        $distributed_sheets_side_bar = "distributed_sheets_side_bar";  
        $users_closer = User::role('Closer')->get();  
        $created_at = '' ;  
        $date_type = '';
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = '';
        } 
        if($request->date_type != null) {
            $date_type = $request->date_type ;
        }else{
            $date_type = '';
        } 
        return view('app.sheets.distributedsheets')
        ->with('distributed_sheets_side_bar',$distributed_sheets_side_bar) 
        ->with('users_closer',$users_closer)      
        ->with('date_type', $date_type) 
        ->with('created_at',$created_at);
    }
    
    public function getDistributedSheetsFilterData( $date_type , $created_at )//except distributed sheet
    {  
        $created_at = date("Y-m-d", strtotime($created_at));
        // dd($date_type .' '. $created_at );
        if($date_type === 'created_at'){
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->whereDate('sheets.created_at', $created_at) 
                        ->where('sheets.closer_id','!=', 0) 
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 

        } else  if($date_type === 'distributed_at') {
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status')   
                        ->whereDate('sheets.distributed_at', $created_at) 
                        ->where('sheets.closer_id','!=', 0) 
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            
        } else {
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->where('sheets.closer_id','!=', 0)  
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 

        } 
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    if( $sheet->status != 0 ){
                        $status .=  $sheet->name  ;
                    }else{
                        $status .=  'Aucun status'  ;                        
                    } 
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="redistribuer">'; 
                    $show .=  ' <span class="ti-settings"></span>';     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }                    

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status','multi_selection', 'action', 'created_at', 'distributed_at'])   
                ->make(true);           
    }
 



    public function editReservedSheet($id)
    {    
        $sheet = Sheet::findOrFail($id);
        $status = Status::all();
        $sheets_side_bar = "sheets_side_bar";
        $updated_by = $sheet->updated_by ; 
        $logedUser = Auth()->user(); 
        $last_update_by = 'aucun';
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;

        $show_btn_etat = true;

        $dc_all_agesArray = $sheet->dc_all_ages;                 
        $dc_all_agesArray = explode(',', $dc_all_agesArray);
        $dc_all_ages = [];
         
        foreach ($dc_all_agesArray as $key => $age) {
            array_push($dc_all_ages,  $age  );
            // dd($sheet->dependent_children);
            if($key+1 === $sheet->dependent_children){
                 break;
            }
        }

        if($logedUser->hasRole('Admin|Closer')){
            if($updated_by != 0){
                $user = User::findOrFail($updated_by);
                $last_update_by = $user->first_name . ' ' . $user->last_name ;
            }                
        }else{            
            $last_update_by = 'aucun';
        } 

        // dd($dc_all_ages);
        return view('app.sheets.editform')
        ->with('sheet', $sheet)
        ->with('dc_all_ages', $dc_all_ages)
        ->with('status', $status)
        ->with('header', $header)
        ->with('last_update_by', $last_update_by)
        ->with('sheets_side_bar', $sheets_side_bar)
        ->with('show_btn_etat', $show_btn_etat); 
    } 
 
    public function getReservedSheets() 
    {          
        $reserved_sheets_all = "reserved_sheets_all";
        $reserved_sheets =  "reserved_sheets";
        $users_closer = User::role('Closer')->get();      
        $created_at ='';   
        return view('app.sheets.reservedsheets')   
        ->with('created_at', $created_at) 
        ->with('users_closer', $users_closer)  
        ->with('reserved_sheets', $reserved_sheets)  
        ->with('reserved_sheets_all', $reserved_sheets_all);
    }
    
    public function getReservedSheetsData() 
    {  
        $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.closer_id') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.status', 4) 
                    ->where('sheets.active', 1) 
                    ->where('sheets.state', 0) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">'; 
                    $status .=  $sheet->name  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservedSheet', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }     

                    if($sheet->has_reservation != 1){
                        $show .= '<a href="'. route('addReservation', $sheet->id).'"' ;
                        $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                        $show .=  'data-title="Ajouter Réservation" style="margin-left: 2px;background-color: #702963;border-color: #702963;">';
                        $show .=  '<i class="ti-pencil-alt" aria-hidden="true"></i> </a>';
                    }                

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at','distributed_at'])   
                ->make(true);           
    }

    public function getReservedSheetsFilterPage(Request $request)
    {
        $reserved_sheets_side_bar = "reserved_sheets_side_bar";  
        $users_closer = User::role('Closer')->get();  
        $created_at = '' ;  
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = '';
        } 
        return view('app.sheets.reservedsheets')
        ->with('reserved_sheets_side_bar',$reserved_sheets_side_bar) 
        ->with('users_closer',$users_closer)  
        ->with('created_at',$created_at);
    }
    
    public function getReservedSheetsFilterData( $created_at ) 
    { 
        if($created_at != '' ){
            $created_at = date("Y-m-d", strtotime($created_at));            
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->whereDate('sheets.created_at', $created_at)  
                        ->where('sheets.status', 4) 
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get();  
        } else {  
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->where('sheets.status', 4) 
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get();  
            
        }  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">'; 
                    $status .=  $sheet->name  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservedSheet', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }     

                    if($sheet->has_reservation != 1){
                        $show .= '<a href="'. route('addReservation', $sheet->id).'"' ;
                        $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                        $show .=  'data-title="Ajouter Réservation" style="margin-left: 2px;background-color: #702963;border-color: #702963;">';
                        $show .=  '<i class="ti-pencil-alt" aria-hidden="true"></i> </a>';
                    }                

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at','distributed_at'])   
                ->make(true);        
    } 
 


    public function getConfirmedList() 
    {           
        $reserved_sheets = "reserved_sheets";  
        $confirmed_sheets = "confirmed_sheets";      
        $created_at ='';   
        return view('app.sheets.confirmed')   
        ->with('created_at', $created_at)   
        ->with('reserved_sheets', $reserved_sheets)
        ->with('confirmed_sheets', $confirmed_sheets);
    }

    public function getConfirmedListData() 
    {  
        $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.closer_id') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.status', 4) 
                    ->where('sheets.active', 1) 
                    ->where('sheets.state', 1) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">'; 
                    $status .=  $sheet->name  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservedSheet', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }     

                    if($sheet->has_reservation != 1){
                        $show .= '<a href="'. route('addReservation', $sheet->id).'"' ;
                        $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                        $show .=  'data-title="Ajouter Réservation" style="margin-left: 2px;background-color: #702963;border-color: #702963;">';
                        $show .=  '<i class="ti-pencil-alt" aria-hidden="true"></i> </a>';
                    }                

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at','distributed_at'])   
                ->make(true);           
    }
    
    public function getConfirmedListPage(Request $request)
    {
        $reserved_sheets = "reserved_sheets";  
        $confirmed_sheets = "confirmed_sheets";     
        $created_at = '' ;  
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = '';
        } 
        return view('app.sheets.confirmed')
        ->with('reserved_sheets',$reserved_sheets) 
        ->with('confirmed_sheets',$confirmed_sheets)  
        ->with('created_at',$created_at);
    }

    public function getConfirmedListFilterData( $created_at ) 
    { 
        if($created_at != '' ){    
            $created_at = date("Y-m-d", strtotime($created_at));        
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->whereDate('sheets.created_at', $created_at)  
                        ->where('sheets.status', 4) 
                        ->where('sheets.active', 1) 
                        ->where('sheets.state', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get();  
        } else {  
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->where('sheets.status', 4) 
                        ->where('sheets.active', 1) 
                        ->where('sheets.state', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get();  
            
        }  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">'; 
                    $status .=  $sheet->name  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservedSheet', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }     

                    if($sheet->has_reservation != 1){
                        $show .= '<a href="'. route('addReservation', $sheet->id).'"' ;
                        $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                        $show .=  'data-title="Ajouter Réservation" style="margin-left: 2px;background-color: #702963;border-color: #702963;">';
                        $show .=  '<i class="ti-pencil-alt" aria-hidden="true"></i> </a>';
                    }                

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at','distributed_at'])   
                ->make(true);        
    } 


    
    
    public function getCancelledList() 
    {           
        $reserved_sheets = "reserved_sheets";  
        $cancelled_sheets = "cancelled_sheets";      
        $created_at ='';   
        return view('app.sheets.cancelled')   
        ->with('created_at', $created_at)   
        ->with('reserved_sheets', $reserved_sheets)
        ->with('cancelled_sheets', $cancelled_sheets);
    }

    public function getCancelledListData() 
    {  
        $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.closer_id') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.status', 4) 
                    ->where('sheets.active', 1) 
                    ->where('sheets.state', 2) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">'; 
                    $status .=  $sheet->name  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservedSheet', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }     

                    if($sheet->has_reservation != 1){
                        $show .= '<a href="'. route('addReservation', $sheet->id).'"' ;
                        $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                        $show .=  'data-title="Ajouter Réservation" style="margin-left: 2px;background-color: #702963;border-color: #702963;">';
                        $show .=  '<i class="ti-pencil-alt" aria-hidden="true"></i> </a>';
                    }                

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at','distributed_at'])   
                ->make(true);           
    }
    
    public function getCancelledListPage(Request $request)
    {
        $reserved_sheets = "reserved_sheets";  
        $cancelled_sheets = "cancelled_sheets";     
        $created_at = '' ;  
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = '';
        } 
        return view('app.sheets.cancelled')
        ->with('reserved_sheets',$reserved_sheets) 
        ->with('cancelled_sheets',$cancelled_sheets)  
        ->with('created_at',$created_at);
    }

    public function getCancelledListFilterData( $created_at ) 
    { 
        if($created_at != '' ){  
            $created_at = date("Y-m-d", strtotime($created_at));
                      
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->whereDate('sheets.created_at', $created_at)  
                        ->where('sheets.status', 4) 
                        ->where('sheets.active', 1) 
                        ->where('sheets.state', 2) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get();  
        } else {  
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->where('sheets.status', 4) 
                        ->where('sheets.active', 1) 
                        ->where('sheets.state', 2) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get();  
            
        }  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {                   
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">'; 
                    $status .=  $sheet->name  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservedSheet', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }     

                    if($sheet->has_reservation != 1){
                        $show .= '<a href="'. route('addReservation', $sheet->id).'"' ;
                        $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                        $show .=  'data-title="Ajouter Réservation" style="margin-left: 2px;background-color: #702963;border-color: #702963;">';
                        $show .=  '<i class="ti-pencil-alt" aria-hidden="true"></i> </a>';
                    }                

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at','distributed_at'])   
                ->make(true);        
    } 
 




    public function getReservedSheetsFinalStep() 
    {           
        $rserv_form_side_bar = "rserv_form_side_bar";   
        $reserved_sheets_finalstep_side_bar = "reserved_sheets_finalstep_side_bar";   
        $reserved_at ='';    
        return view('app.sheets.reservedsheetsfinalstep')   
        ->with('reserved_at', $reserved_at)   
        ->with('reserved_sheets_finalstep_side_bar', $reserved_sheets_finalstep_side_bar)
        ->with('rserv_form_side_bar', $rserv_form_side_bar) ;
    }

    public function getReservedSheetsFinalStepData() 
    {  
        $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.closer_id') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.has_reservation', 1) 
                    ->where('sheets.active', 1) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                }) 
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('viewReservation', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservation', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Déplacer vers">'; 
                    $show .=  ' <span class="ti-settings"></span>';     
                    $show .=  ' </a>';   

                    return $show;
                })
                ->editColumn('nbr_pax', function ($sheet) {                  
                    $status ='<div class="text-center" style="margin-top: 8px;">'; 
                    $status .='<span class="label label-sm label-info " >'; 
                    $status .=  $sheet->nbr_pax  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status; 

                })
                ->editColumn('amount', function ($sheet) {                  
                    $status ='<div class="text-center" style="margin-top: 8px;">'; 
                    $status .='<span class="label label-sm label-danger " >'; 
                    $status .=  $sheet->amount  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status; 

                })
                ->editColumn('reserved_at', function ($sheet) {
                    return $sheet->reserved_at ? with(new Carbon($sheet->reserved_at))->format('d/m/Y') : '';
                })  
                ->editColumn('date_arrived', function ($sheet) {
                    return $sheet->date_arrived ? with(new Carbon($sheet->date_arrived))->format('d/m/Y'): '';
                })  
                ->editColumn('date_departure', function ($sheet) {
                    return $sheet->date_departure ? with(new Carbon($sheet->date_departure))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'nbr_pax', 'amount', 'action', 'multi_selection','reserved_at' ])   
                ->make(true);           
    }

    public function getReservedSheetsFinalStepFilterPage(Request $request)
    {

        $rserv_form_side_bar = "rserv_form_side_bar";   
        $reserved_sheets_finalstep_side_bar = "reserved_sheets_finalstep_side_bar";    
        $reserved_at  = '' ;  
        if($request->reserved_at  != null) {
            $reserved_at  = $request->reserved_at  ;
        }else{
            $reserved_at  = '';
        } 

        return view('app.sheets.reservedsheetsfinalstep')  
        ->with('reserved_at',$reserved_at)
        ->with('rserv_form_side_bar',$rserv_form_side_bar)        
        ->with('reserved_sheets_finalstep_side_bar',$reserved_sheets_finalstep_side_bar);
    }
    
    public function getReservedSheetsFinalStepFilterData( $reserved_at ) 
    { 
        if($reserved_at != '' ){     

            $reserved_at = date("Y-m-d", strtotime($reserved_at));
             
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status')   
                        ->whereDate('sheets.reserved_at', $reserved_at) 
                        ->where('sheets.has_reservation', 1) 
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
        } else {            
            $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.closer_id') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->where('sheets.has_reservation', 1) 
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            
        }   
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                }) 
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('viewReservation', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservation', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Déplacer vers">'; 
                    $show .=  ' <span class="ti-settings"></span>';     
                    $show .=  ' </a>';   

                    return $show;
                })
                ->editColumn('nbr_pax', function ($sheet) {                  
                    $status ='<div class="text-center" style="margin-top: 8px;">'; 
                    $status .='<span class="label label-sm label-info " >'; 
                    $status .=  $sheet->nbr_pax  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status; 

                })
                ->editColumn('amount', function ($sheet) {                  
                    $status ='<div class="text-center" style="margin-top: 8px;">'; 
                    $status .='<span class="label label-sm label-danger " >'; 
                    $status .=  $sheet->amount  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status; 

                })
                ->editColumn('reserved_at', function ($sheet) {
                    return $sheet->reserved_at ? with(new Carbon($sheet->reserved_at))->format('d/m/Y'): '';
                })  
                ->editColumn('date_arrived', function ($sheet) {
                    return $sheet->date_arrived ? with(new Carbon($sheet->date_arrived))->format('d/m/Y') : '';
                })  
                ->editColumn('date_departure', function ($sheet) {
                    return $sheet->date_departure ? with(new Carbon($sheet->date_departure))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'nbr_pax', 'amount', 'action', 'multi_selection','reserved_at' ])   
                ->make(true);           
    }
 

    public function getSheetCategoryPage($id) 
    {            
        $rserv_form_side_bar = "rserv_form_side_bar";
        $category = Category::findOrFail($id);
        $category_side_bar_list = $category->title;

        return view('app.sheets.sheetcategory')   
        ->with('category', $category)  
        ->with('category_side_bar_list', $category_side_bar_list) 
        ->with('rserv_form_side_bar', $rserv_form_side_bar) ;
    } 
  

    public function getSheetCategoryPageData($id) 
    {   
        $sheets = DB::table('category_sheet')
                    ->join('categories', 'category_sheet.category_id', '=', 'categories.id') 
                    ->join('sheets', 'category_sheet.sheet_id', '=', 'sheets.id') 
                    ->where('sheets.has_reservation', 1)
                    ->where('sheets.active', 1)
                    ->where('categories.id',$id)
                    ->select('sheets.*')
                    ->get(); 

        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })  
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('viewReservation', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('editReservation', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';                      

                    return $show;
                })
                ->editColumn('nbr_pax', function ($sheet) {                  
                    $status ='<div class="text-center" style="margin-top: 8px;">'; 
                    $status .='<span class="label label-sm label-info " >'; 
                    $status .=  $sheet->nbr_pax  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status; 

                })
                ->editColumn('amount', function ($sheet) {                  
                    $status ='<div class="text-center" style="margin-top: 8px;">'; 
                    $status .='<span class="label label-sm label-danger " >'; 
                    $status .=  $sheet->amount  ;  
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status; 

                })
                ->editColumn('reserved_at', function ($sheet) {
                    return $sheet->reserved_at ? with(new Carbon($sheet->reserved_at))->format('d/m/Y'): '';
                })  
                ->editColumn('date_arrived', function ($sheet) {
                    return $sheet->date_arrived ? with(new Carbon($sheet->date_arrived))->format('d/m/Y') : '';
                })  
                ->editColumn('date_departure', function ($sheet) {
                    return $sheet->date_departure ? with(new Carbon($sheet->date_departure))->format('d/m/Y') : '';
                })  
                ->rawColumns([ 'order', 'client_full_name', 'nbr_pax', 'amount', 'action' ,'reserved_at' ])   
                ->make(true);           
    }


    public function getDeletedSheets()
    {     
        $deleted_sheets_side_bar = "deleted_sheets_side_bar";  
        $users_closer = User::role('Closer')->get();     
        return view('app.sheets.deletedsheets')
        ->with('deleted_sheets_side_bar',$deleted_sheets_side_bar) 
        ->with('users_closer',$users_closer) ;
    }

    public function getDeletedSheetsData()
    { 
        $sheets = DB::table('sheets')
                ->join('users', 'users.id', '=', 'sheets.created_by') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.active', 0) 
                ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>'; 
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Distribuer">'; 
                    $show .=  ' <span class="ti-settings"></span>';     
                    $show .=  ' </a>';                      

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status','multi_selection', 'action', 'created_at'])   
                ->make(true);
    }






















    

    public function getFilterUndistributedByDate(Request $request)
    {          
        // dd($request->all()); 
        $created_at = $request->created_at;
        if($created_at != null){
            // get sheets by  date
            $sheets = Sheet::where('closer_id',0)->whereDate('created_at', $created_at)->where('active', 1)->get(); 
        }else{ 
            Toastr::error('Séletionnez une date', 'Filter Fiches', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } 
        $undistributed_sheets_side_bar = "undistributed_sheets_side_bar";  
        $count_sheets = count($sheets); 
        $all_status = Status::all();
        $users_closer = User::role('Closer')->get();     
        $statusId ='';
        $created_at ='';   
        return view('app.sheets.undistributedsheets')
        ->with('sheets', $sheets) 
        ->with('all_status', $all_status) 
        ->with('count_sheets', $count_sheets)
        ->with('statusId', $statusId) 
        ->with('created_at', $created_at) 
        ->with('users_closer', $users_closer)  
        ->with('undistributed_sheets_side_bar', $undistributed_sheets_side_bar);
    }



    public function getSheetsByAgentId($userName,$id)
    {          
        $users_side_bar = "users_side_bar"; 
        $sheets = Sheet::where('created_by', $id)->where('active', 1)->get();
        $count_sheets = count($sheets); 
        $users_closer = User::role('Closer')->get();
        $agent = User::findOrFail($id);
        $agent_full_name =  $agent->first_name . ' ' . $agent->last_name ; 
        $all_status = Status::all();
        $agent_id = $id;
        $statusId ='';
        $created_at ='';
        return view('app.sheets.index')
        ->with('sheets', $sheets) 
        ->with('all_status', $all_status) 
        ->with('agent_id', $agent_id) 
        ->with('statusId', $statusId) 
        ->with('created_at', $created_at) 
        ->with('agent_full_name', $agent_full_name) 
        ->with('agent_last_name', $agent->last_name) 
        ->with('count_sheets', $count_sheets)
        ->with('users_closer', $users_closer) 
        ->with('users_side_bar', $users_side_bar)  ;
    }

    
    public function getSheetsByAgentPage($userName,$id)
    {      
        $users_side_bar = "users_side_bar";
        $users_closer = User::role('Closer')->get();
        $agent = User::findOrFail($id);
        $agent_full_name =  $agent->first_name . ' ' . $agent->last_name ; 
        $all_status = Status::all();
        $agent_id = $id;
        $statusId ='x';
        $created_at ='';
        return view('app.sheets.indexx')
        ->with('all_status', $all_status) 
        ->with('agent_id', $agent_id) 
        ->with('statusId', $statusId) 
        ->with('created_at', $created_at) 
        ->with('agent_full_name', $agent_full_name) 
        ->with('agent_last_name', $agent->last_name)  
        ->with('users_closer', $users_closer) 
        ->with('users_side_bar', $users_side_bar)  ;
    }
    
    public function getSheetsByAgentData($id)
    {  
        $sheets = DB::table('sheets')
                ->join('users', 'users.id', '=', 'sheets.created_by') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.created_by', $id) 
                ->where('sheets.active', 1) 
                ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" '; 
                    if( $sheet->closer_id != 0){
                        $show .=  ' data-title="redistribuer">'; 
                        $show .=  ' <span class="ti-wand"></span>';
                    }  else{
                        $show .=  ' data-title="Distribuer">'; 
                        $show .=  ' <span class="ti-settings"></span>';
                    }     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }                    

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at'])   
                ->make(true);
    }
    
    public function getSheetsByAgentFilterPage(Request $request,$userName,$id)
    {      
        // dd($request->all());
        $users_side_bar = "users_side_bar";
        $users_closer = User::role('Closer')->get();
        $agent = User::findOrFail($id);
        $agent_full_name =  $agent->first_name . ' ' . $agent->last_name ; 
        $all_status = Status::all();
        $agent_id = $id;
        $statusId = 'x';
        if($request->status_id != null) {
            $statusId = $request->status_id ;
        }else{
            $statusId = 'x';
        }
        $created_at = 'x' ;  
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = 'x';
        } 
        return view('app.sheets.indexx')
        ->with('all_status', $all_status) 
        ->with('agent_id', $agent_id) 
        ->with('statusId', $statusId) 
        ->with('created_at', $created_at) 
        ->with('agent_full_name', $agent_full_name) 
        ->with('agent_last_name', $agent->last_name)  
        ->with('users_closer', $users_closer) 
        ->with('users_side_bar', $users_side_bar)  ;
    }

    public function getSheetsByAgentFilterData($status_id, $created_at, $id)
    {  
        // $sheets = DB::table('sheets')
        //         ->join('users', 'users.id', '=', 'sheets.created_by') 
        //         ->join('status', 'status.id', '=', 'sheets.status')  
        //         ->where('sheets.created_by', $id) 
        //         ->where('sheets.active', 1) 
        //         ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
        //         ->orderBy('sheets.id', 'asc')
        //         ->get();  

        
        // dd($status_id .' '. $created_at );
        if($status_id != 'x' && $created_at != 'x'){
            // get sheets by status and date  
             
            $created_at = date("Y-m-d", strtotime($created_at));
            $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status')               
                    ->where('sheets.status', $status_id)
                    ->whereDate('sheets.created_at', $created_at)  
                    ->where('sheets.created_by', $id) 
                    ->where('sheets.active', 1)   
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();                 
            
        }elseif($status_id != 'x' && $created_at === 'x'){
            if($status_id == 0 ){
                // get sheets by status only 
                $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.created_by') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->where('sheets.closer_id', 0) 
                        ->where('sheets.created_by', $id) 
                        ->where('sheets.active', 1)   
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            }else{
                // get sheets by status only 
                $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.created_by') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->where('sheets.status', $status_id)  
                        ->where('sheets.created_by', $id) 
                        ->where('sheets.active', 1)   
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            }
        }elseif($status_id === 'x' && $created_at !='x'){
            //get sheets by date only 
            $created_at = date("Y-m-d", strtotime($created_at));
            $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status') 
                    ->whereDate('sheets.created_at', $created_at)  
                    ->where('sheets.created_by', $id) 
                    ->where('sheets.active', 1)   
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get(); 
        }elseif($status_id === 'x' && $created_at ==='x'){ 
            $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status')   
                    ->where('sheets.created_by', $id) 
                    ->where('sheets.active', 1) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get(); 
        } 

        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" '; 
                    if( $sheet->closer_id != 0){
                        $show .=  ' data-title="redistribuer">'; 
                        $show .=  ' <span class="ti-wand"></span>';
                    }  else{
                        $show .=  ' data-title="Distribuer">'; 
                        $show .=  ' <span class="ti-settings"></span>';
                    }     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }                    

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at'])   
                ->make(true);
    }







    public function getSheetsFilterByAgentId(Request $request)
    {          
        $agent_last_name = $request->agent_last_name;
        $status_id = $request->status_id;        
        $agent_id = $request->agent_id; 
        $created_at = $request->created_at;
     
        // $created_at = Carbon::parse($created_at);
        // dd($created_at);
        $users_side_bar = "users_side_bar"; 
        if($status_id != null && $created_at != null){
            // get sheets by status and date
            $sheets = Sheet::where('created_by', $agent_id)->where('status', $status_id)->whereDate('created_at', $created_at)->where('active', 1)->get();
            // dd($sheets);
        }elseif($status_id != null && $created_at === null){
            // get sheets by status only
            $sheets = Sheet::where('created_by', $agent_id)->where('status', $status_id)->where('active', 1)->get();
            // dd($sheets);
        }elseif($status_id === null && $created_at !=null){
            //get sheets by date only
            $sheets = Sheet::where('created_by', $agent_id)->whereDate('created_at', $created_at)->where('active', 1)->get();
            // dd($sheets);
        }elseif($status_id === null && $created_at === null){ 
            Toastr::error('Séletionnez un status ou une date', 'Filter Fiches', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        if($status_id === null){
            $statusId = 0 ;
        }else{
            $statusId = $status_id ;
        } 
        $count_sheets = count($sheets); 
        $users_closer = User::role('Closer')->get();
        $agent = User::findOrFail($agent_id);
        $agent_full_name =  $agent->first_name . ' ' . $agent->last_name ; 
        $all_status = Status::all();
        return view('app.sheets.index')
        ->with('sheets', $sheets) 
        ->with('all_status', $all_status)  
        ->with('agent_id', $agent_id) 
        ->with('statusId', $statusId)  
        ->with('created_at', $created_at) 
        ->with('agent_last_name', $agent->last_name) 
        ->with('agent_full_name', $agent_full_name) 
        ->with('count_sheets', $count_sheets)
        ->with('users_closer', $users_closer) 
        ->with('users_side_bar', $users_side_bar)  ;
    }
     

    public function getCloserFilter(Request $request)
    {          
         // dd($request->all());
        $agent_last_name = $request->agent_last_name;
        $status_id = $request->status_id;        
        $agent_id = $request->agent_id; 
        $created_at = $request->created_at;
     
        // $created_at = Carbon::parse($created_at);
        // dd($created_at);
       
        $sheets_side_bar = "sheets side bar";  
        if($status_id != null && $created_at != null){
            // get sheets by status and date
            $sheets = Sheet::where('closer_id',Auth()->user()->id)->where('status', $status_id)->whereDate('created_at', $created_at)->where('active', 1)->get();
            // dd($sheets);
        }elseif($status_id != null && $created_at === null){
            // get sheets by status only
            $sheets = Sheet::where('closer_id',Auth()->user()->id)->where('status', $status_id)->where('active', 1)->get();
            // dd($sheets);
        }elseif($status_id === null && $created_at !=null){
            //get sheets by date only
            $sheets = Sheet::where('closer_id',Auth()->user()->id)->where('status','!=', 4)->whereDate('created_at', $created_at)->where('active', 1)->get();
            // dd($sheets);
        }elseif($status_id === null && $created_at === null){ 
            Toastr::error('Séletionnez un status ou une date', 'Filter Fiches', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        if($status_id == 0){
            $statusId = 'aucun' ;
        }else{
            $statusId = $status_id ;
        }  
        $count_sheets = count($sheets); 
        $users_closer = User::role('Closer')->get();  
        $all_status = Status::all()->except(4);  
         
        return view('app.sheets.index')
        ->with('sheets', $sheets) 
        ->with('all_status', $all_status) 
        ->with('statusId', $statusId)  
        ->with('created_at', $created_at)   
        ->with('count_sheets', $count_sheets)
        ->with('users_closer', $users_closer) 
        ->with('sheets_side_bar', $sheets_side_bar)  ;
    }

 
    
    public function getCloserSheets()
    {          
        $sheets_side_bar = "sheets side bar";       
        $all_status = Status::all()->except(4);            
        $statusId ='x';
        $created_at ='';   
        return view('app.sheets.closersheets') 
        ->with('all_status', $all_status)  
        ->with('created_at', $created_at) 
        ->with('statusId', $statusId)  
        ->with('sheets_side_bar', $sheets_side_bar) ;
    }

    public function getCloserSheetsData()
    { 
        $closer_id = Auth()->user()->id; 
        $sheets = DB::table('sheets') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.closer_id', $closer_id) 
                ->where('sheets.status', 0) 
                ->where('sheets.active', 1) 
                ->select( 'sheets.*','status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();   
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    if($sheet->status != 0){
                        $status .=  $sheet->name  ;
                    }else{
                        $status .=  "Aucun status"  ;
                    }
                    
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifierx">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';                      
                    if(Auth()->user()->can('send-email')){
                        if($sheet->email != ''){
                            $show .= '<a id="openSendEmailToClient" ' ;
                            $show .= ' value="'. $sheet->id .'" ' ; 
                            $show .= ' name="'.  $this->order  .'" ' ;  
                            $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                            $show .=  ' data-title="Envoie d\'email">'; 
                            $show .=  ' <span class="ti-email"></span>';     
                            $show .=  ' </a>';  
                        }   
                    }                 

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at', 'distributed_at'])   
                ->make(true);
    }
 
        
    public function getCloserSheetsFilterPage(Request $request)
    {            
        // dd($request->all());
        $sheets_side_bar = "sheets side bar";       
        $all_status = Status::all()->except(4);            
        $statusId = 'x';
        if($request->status_id != null) {
            $statusId = $request->status_id ;
        }else{
            $statusId = 'x';
        }
        $created_at = 'x' ;  
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = 'x';
        }   
        return view('app.sheets.closersheets') 
        ->with('all_status', $all_status)  
        ->with('created_at', $created_at) 
        ->with('statusId', $statusId)  
        ->with('sheets_side_bar', $sheets_side_bar) ;
    }


    public function getCloserSheetsFilterData($status_id, $created_at)
    { 
        $created_at = date("Y-m-d", strtotime($created_at));
        // dd($created_at);
        $closer_id = Auth()->user()->id; 
        $sheets = DB::table('sheets') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.closer_id', $closer_id) 
                ->where('sheets.status', 0) 
                ->where('sheets.active', 1) 
                ->select( 'sheets.*','status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();
                
        // dd($status_id .' '. $created_at );
        if($status_id != 'x' && $created_at != 'x'){
            // get sheets by status and date   
            $sheets = DB::table('sheets') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.closer_id', $closer_id) 
                    ->where('sheets.status', $status_id) 
                    ->whereDate('sheets.created_at', $created_at) 
                    ->where('sheets.active', 1) 
                    ->select( 'sheets.*','status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();     
        }elseif($status_id != 'x' && $created_at === 'x'){
            if($status_id == 0 ){ 
                $sheets = DB::table('sheets') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->where('sheets.closer_id', $closer_id) 
                        ->where('sheets.status', 0)   
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*','status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            }else{                         
                $sheets = DB::table('sheets') 
                        ->join('status', 'status.id', '=', 'sheets.status')  
                        ->where('sheets.closer_id', $closer_id) 
                        ->where('sheets.status', $status_id)   
                        ->where('sheets.active', 1) 
                        ->select( 'sheets.*','status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get();
            }
        }elseif($status_id === 'x' && $created_at !='x'){              
            $sheets = DB::table('sheets') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.closer_id', $closer_id)  
                ->whereDate('sheets.created_at', $created_at)  
                ->where('sheets.active', 1) 
                ->select( 'sheets.*','status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();
        }elseif($status_id === 'x' && $created_at ==='x'){ 
            $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.active', 1) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get(); 
            $sheets = DB::table('sheets') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.closer_id', $closer_id)  
                ->where('sheets.status', 0)  
                ->where('sheets.active', 1) 
                ->select( 'sheets.*','status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();
        }   
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    if($sheet->status != 0){
                        $status .=  $sheet->name  ;
                    }else{
                        $status .=  "Aucun status"  ;
                    }
                    
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                }) 
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifierx">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';                      
                    if(Auth()->user()->can('send-email')){
                        if($sheet->email != ''){
                            $show .= '<a id="openSendEmailToClient" ' ;
                            $show .= ' value="'. $sheet->id .'" ' ; 
                            $show .= ' name="'.  $this->order  .'" ' ;  
                            $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                            $show .=  ' data-title="Envoie d\'email">'; 
                            $show .=  ' <span class="ti-email"></span>';     
                            $show .=  ' </a>';  
                        }   
                    }                 

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->editColumn('distributed_at', function ($sheet) {
                    return $sheet->distributed_at ? with(new Carbon($sheet->distributed_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status', 'action', 'created_at', 'distributed_at'])   
                ->make(true);
    }
 



     

    public function distributeToCloser(Request $request)
    {
        if($request->closer_id == 0){
            Toastr::error('Selectioner un closer !', 'Distribuer une fiche', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }  
        $sheet = Sheet::findOrFail($request->sheet_id);
        $sheet->closer_id = $request->closer_id ;
        $sheet->distributed_at =  Date('Y-m-d');
        $sheet->update();         
        Toastr::success('Votre Fiche a été distribué avec succès', 'Distribuer une fiche', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function multiDistributeToCloser(Request $request)
    {
        if($request->closer_id == 0){
            Toastr::error('Selectioner un closer !', 'Multi Distribution', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        $sheetsList = $request->sheetsList;
        $sheets_list = explode("," ,$sheetsList);
        //dd($sheets_list); 
        $test = "";
        foreach ($sheets_list as $sheet => $id) { 
            $test .= $id;
            $sheet = Sheet::findOrFail($id);
            $sheet->closer_id = $request->closer_id ;
            $sheet->distributed_at =  Date('Y-m-d');
            // $sheet->w_age = 44 ;
            $sheet->update(); 
        }
        Toastr::success('Les fiches ont été distribué avec succès', 'Multi Distribution', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function generatePdf($id)
    {        
        $sheet = Sheet::findOrFail($id);
        $header = Header::findOrFail($sheet->header); 
        $sheets_side_bar = "sheets_side_bar"; 
        $logedUser = Auth()->user(); 
                
        $dc_all_agesArray = $sheet->dc_all_ages;                 
        $dc_all_agesArray = explode(',', $dc_all_agesArray);
        $dc_all_ages = [];
         
        foreach ($dc_all_agesArray as $key => $age) {
            array_push($dc_all_ages,  $age  );
            // dd($sheet->dependent_children);
            if($key+1 === $sheet->dependent_children){
                break;
            }
        }

        $file_name = $sheet->client_code .'-' . date('Y-m-d').'.pdf' ;
        $pdf = PDF::loadView('app.sheets.pdf', [
            'sheet' =>$sheet ,
            'header' =>$header ,
            'dc_all_ages' =>$dc_all_ages ,
        ]);        

        return $pdf->download($file_name);
    }
    
    public function viewPdf($id)
    {        
        $sheet = Sheet::findOrFail($id);        
        $header = Header::findOrFail($sheet->header); 
        $dc_all_agesArray = $sheet->dc_all_ages;                 
        $dc_all_agesArray = explode(',', $dc_all_agesArray);
        $dc_all_ages = [];
         
        foreach ($dc_all_agesArray as $key => $age) {
            array_push($dc_all_ages,  $age  );
            // dd($sheet->dependent_children);
            if($key+1 === $sheet->dependent_children){
                 break;
            }
        }
        $sheets_side_bar = "sheets_side_bar";
        $updated_by = $sheet->updated_by ;
        $createdBy  = $sheet->created_by  ;
        $last_update_by = 'aucun';
        $created_by = 'aucun';
        $logedUser = Auth()->user(); 
        if($logedUser->hasRole('Admin|Closer')){ 
            if($updated_by != 0){
                $updatedByUser = User::findOrFail($updated_by);
                $createdByUser = User::findOrFail($createdBy);  
                $last_update_by = $updatedByUser->first_name . ' ' . $updatedByUser->last_name ; 
                $created_by = $createdByUser->first_name . ' ' . $createdByUser->last_name ;                  
            }       
        }else{            
            $last_update_by = 'aucun';
            $created_by = 'aucun';
        }  
        
        return view('app.sheets.pdf')
        ->with('sheet', $sheet)
        ->with('dc_all_ages', $dc_all_ages)
        ->with('header', $header)
        ->with('last_update_by', $last_update_by)
        ->with('created_by', $created_by)
        ->with('sheets_side_bar', $sheets_side_bar); 
 
    }

    public function sendEmail(Request $request)
    {
        // dd($request->all());
        $sheet = Sheet::findOrFail($request->sheet_id); 

        // send email to email header dynamique
        $header = Header::findOrFail($sheet->header);  
        
        $client_name ="-----";
        if($sheet->m_last_name !=""){
            $client_name = $sheet->m_last_name;
        }else{
            $client_name = $sheet->w_last_name;
        }
        $data_email = [ 
            
            //'email' => 'info@visa-traveller.com',
            
            'email' => $header->email,
            'email_client' => $sheet->email,
            'client_code' => $sheet->client_code, 
            'client_name' => $client_name,
            'signature' => $request->signature,
            'profile' => $request->profile, 
            'destination' => $request->destination
        ];
        // dd($data_email);
        Mail::to($data_email['email'])->send(new SendEmailToClient($data_email));     
      
        Toastr::success('Email envoyer avec succès', 'Envoyer un email', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }
    public function emailView(){
        return view('app.mail.test');
    }

    public function getPieChartData()
    {
        // get count sheets all time
        // count NRP sheets
        // $countNoneSheets =  Sheet::where('status', 0)->count(); 
        $countNRPSheets =  Sheet::where('status', 1)->where('active', 1)->count(); 
        $countRefusSheets =  Sheet::where('status', 2)->where('active', 1)->count(); 
        $countRappelSheets =  Sheet::where('status', 3)->where('active', 1)->count(); 
        $countReservationSheets =  Sheet::where('status', 4)->where('active', 1)->count(); 

        $data = [
            // $countNoneSheets,
            $countReservationSheets,
            $countRappelSheets,
            $countNRPSheets, 
            $countRefusSheets, 
        ];
        return response()->json([ 
            'data' => $data,         
            // 'countNoneSheets' => $countNoneSheets,
            'countReservationSheets' => $countReservationSheets ,
            'countRappelSheets' => $countRappelSheets,
            'countNRPSheets' => $countNRPSheets, 
            'countRefusSheets' => $countRefusSheets
        ], 200);
    }

    public function getSheetsPerAgentIdAndDay($id, $datespecific)
    {
        // $datespecific = Carbon::parse($datespecific)->format('Y-m-d');
        // dd( $datespecific);
        $datespecific = date("Y-m-d", strtotime($datespecific)); 
        //  dd($datespecific);
        // dd($datespecific);
        $sheetPerAgentAndDay = Sheet::where('created_by', $id)->whereDate('created_at', $datespecific)->where('active', 1)->count();// between 2 dates
        // dd($sheetPerAgentAndDay);
        return response()->json([ 
                    'allsheets' => $sheetPerAgentAndDay,
                ],200);
    }

    public function getAgentStatisticPage($id)
    {
        $statistic_side_bar = "statistic_side_bar";
        $current_agent = User::findOrFail($id);
        $list_agent =  User::role('Agent')->get();
        return view('app.statistic.agentchart')
        ->with('list_agent', $list_agent)
        ->with('current_agent', $current_agent)
        ->with('statistic_side_bar', $statistic_side_bar);
    }
    public function getGlobalAgentStatisticPage()
    {
        $statistic_side_bar = "statistic_side_bar";
        $stati_agent_side_bar = "stati_agent_side_bar"; 
        $list_agent =  User::role('Agent')->where('active', 1)->get();  
        return view('app.statistic.agentglobalchart')
        ->with('list_agent', $list_agent) 
        ->with('stati_agent_side_bar', $stati_agent_side_bar)
        ->with('statistic_side_bar', $statistic_side_bar);
    } 
    public function getSheetsPerAgentId($id)
    {
        $sheetPerAgent = Sheet::where('created_by', $id)->where('active', 1)->count(); 
 
        return response()->json([ 
            'allsheets' => $sheetPerAgent,
        ],200);
    }
    public function getAllAgentsStatistic()
    {
        $statistic_all_agents =  User::role('Agent')->where('active', 1)->get();
        $countPerAgent = array();
        $nameAgent = array();
        $dataColor = array();
        foreach ($statistic_all_agents as $agent) {
                $countAgentSheets = Sheet::where('created_by', $agent->id)->where('active', 1)->count();
                $full_name = $agent->first_name .' ' . $agent->last_name ;
                 array_push($countPerAgent,  $countAgentSheets  );
                 array_push($nameAgent,  $full_name  );
                 array_push($dataColor,  "#3e95cd"  );
        }
        // dd($countPerAgent);
        return response()->json([ 
            'nameAgent' => $nameAgent,
            'countPerAgent' => $countPerAgent, 
            'dataColor' => $dataColor, 
        ],200);
    }
    
    public function getAllAgentsStatisticPerDay($datebegin, $dateend)
    {

        $datebegin = date("Y-m-d", strtotime($datebegin));
        $dateend = date("Y-m-d", strtotime($dateend));

        // dd($datebegin .' --- '. $dateend);   

        $statistic_all_agents =  User::role('Agent')->where('active', 1)->get();
        
        $countPerAgent = array();
        $nameAgent = array();
        $dataColor = array();
        foreach ($statistic_all_agents as $agent) {
                // $countAgentSheets = Sheet::where('created_by', $agent->id)->whereDate('created_at', $datespecific)->count();
                $countAgentSheets = Sheet::where('created_by', $agent->id)->whereDate('created_at','>=', $datebegin)->whereDate('created_at','<=', $dateend)->where('active', 1)->count();  
                $full_name = $agent->first_name .' ' . $agent->last_name ;
                 array_push($countPerAgent,  $countAgentSheets  );
                 array_push($nameAgent,  $full_name  );
                 array_push($dataColor,  "#3e95cd"  );
        }
        $agentNumbers = count($countPerAgent);
        $contAllZiros =  count(array_keys($countPerAgent, 0));
        // dd($countAllCount.'-'. count(array_keys($countPerAgent, 0)));
        // dd($countPerAgent);
        return response()->json([ 
            'nameAgent' => $nameAgent,
            'countPerAgent' => $countPerAgent, 
            'dataColor' => $dataColor, 
            'agentNumbers' => $agentNumbers, 
            'contAllZiros' => $contAllZiros, 
        ],200);
    }

    public function getCloserStatisticPage($id)
    {
        $statistic_side_bar = "statistic_side_bar";
        $current_closer = User::findOrFail($id); 
        return view('app.statistic.closerchart') 
        ->with('current_closer', $current_closer)
        ->with('statistic_side_bar', $statistic_side_bar);
    }
    public function getSheetsPerCloserId($id)
    {
        $sheetPerCloser = Sheet::where('closer_id', $id)->where('active', 1)->count();  
        $countReservationSheets =  Sheet::where('closer_id', $id)->where('status', 4)->where('active', 1)->count();
        $countRappelSheets =  Sheet::where('closer_id', $id)->where('status', 3)->where('active', 1)->count(); 
        $countNRPSheets =  Sheet::where('closer_id', $id)->where('status', 1)->where('active', 1)->count(); 
        $countRefusSheets =  Sheet::where('closer_id', $id)->where('status', 2)->where('active', 1)->count(); 

        return response()->json([ 
            'sheetPerCloser' => $sheetPerCloser,
            'countReservationSheets' => $countReservationSheets,
            'countRappelSheets' => $countRappelSheets,
            'countNRPSheets' => $countNRPSheets,
            'countRefusSheets' => $countRefusSheets
        ],200);
    }    
    public function getSheetsPerCloserIdAndDate($id, $datebegin, $dateend)
    {
 
        $datebegin = date("Y-m-d", strtotime($datebegin));
        $dateend = date("Y-m-d", strtotime($dateend));
        // dd($datebegin .' ----x ' . $dateend); 


        $sheetPerCloser = Sheet::where('closer_id', $id)->whereDate('updated_at','>=', $datebegin)->whereDate('updated_at','<=', $dateend)->where('active', 1)->count();  
        $countReservationSheets =  Sheet::where('closer_id', $id)->where('status', 4)->whereDate('updated_at','>=', $datebegin)->whereDate('updated_at','<=', $dateend)->where('active', 1)->count();
        $countRappelSheets =  Sheet::where('closer_id', $id)->where('status', 3)->whereDate('updated_at','>=', $datebegin)->whereDate('updated_at','<=', $dateend)->where('active', 1)->count(); 
        $countNRPSheets =  Sheet::where('closer_id', $id)->where('status', 1)->whereDate('updated_at','>=', $datebegin)->whereDate('updated_at','<=', $dateend)->where('active', 1)->count(); 
        $countRefusSheets =  Sheet::where('closer_id', $id)->where('status', 2)->whereDate('updated_at','>=', $datebegin)->whereDate('updated_at','<=', $dateend)->where('active', 1)->count(); 

        return response()->json([ 
            'sheetPerCloser' => $sheetPerCloser,
            'countReservationSheets' => $countReservationSheets,
            'countRappelSheets' => $countRappelSheets,
            'countNRPSheets' => $countNRPSheets,
            'countRefusSheets' => $countRefusSheets
        ],200);
    }

    public function getCloserGlobalStatisticPage()
    {
        $statistic_side_bar = "statistic_side_bar"; 
        $stati_closer_side_bar = "stati_closer_side_bar";
        $list_closer =  User::role('Closer')->where('active', 1)->get();
        return view('app.statistic.closerglobalchart') 
        ->with('list_closer', $list_closer)
        ->with('stati_closer_side_bar', $stati_closer_side_bar)
        ->with('statistic_side_bar', $statistic_side_bar);
    }

    public function addSheetReservation($id) 
    {         
        $reserved_sheets_side_bar = "reserved_sheets_side_bar";   
        $sheet = Sheet::findOrFail($id);        
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;
        // dd($sheet);
        return view('app.sheets.hasreservation')
        ->with('sheet', $sheet)         
        ->with('header', $header)         
        ->with('reserved_sheets_side_bar', $reserved_sheets_side_bar) ;
    }

    public function editReservation($id) 
    {         
        $reserved_sheets_finalstep_side_bar = "reserved_sheets_finalstep_side_bar";    
        $sheet = Sheet::findOrFail($id);        
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;
        // dd($sheet);
        return view('app.sheets.editreservation')
        ->with('sheet', $sheet)         
        ->with('header', $header)         
        ->with('reserved_sheets_finalstep_side_bar', $reserved_sheets_finalstep_side_bar) ;
    }


    public function updateSheetReservation(Request $request, $id)
    {
         //  dd($request->date_arrived );
         //$date_arrived = date("Y-m-d", strtotime($request->date_arrived));
        // dd($date_arrived );
        // dd($request->all());
        $this->validate($request, [ 
            'nbr_pax' => 'required'
        ]); 
          
        $sheet = Sheet::findOrFail($id); 
        
        $sheet->w_last_name = $request->w_last_name;
        $sheet->w_first_name = $request->w_first_name;
        $sheet->w_profession = $request->w_profession;
        $sheet->w_age = $request->w_age;
        $sheet->m_last_name = $request->m_last_name;
        $sheet->m_first_name = $request->m_first_name;
        $sheet->m_profession = $request->m_profession;
        $sheet->m_age = $request->m_age;
        $sheet->tel = $request->tel;
        $sheet->gsm = $request->gsm;
        $sheet->email = $request->email;
        $sheet->address = $request->address; 

        $oldVal = $sheet->has_reservation;
        $sheet->has_reservation = 1;
        $sheet->nbr_pax = $request->nbr_pax;
        $sheet->date_departure =  date("Y-m-d", strtotime($request->date_departure));  
        $sheet->date_arrived = date("Y-m-d", strtotime($request->date_arrived));
        $sheet->dd_flight_number = $request->dd_flight_number;
        $sheet->da_flight_number = $request->da_flight_number;
        $sheet->establishment = $request->establishment;
        $sheet->supplements = $request->supplements;
        $sheet->amount = $request->amount;
        $sheet->comments = $request->comments;
        if($oldVal != 1){
            $sheet->reserved_at = Date('Y-m-d');     
        }
        $sheet->update();

        // dd($sheet);
        //$sheet->update();
        if($oldVal != 1){
            Toastr::success('Votre formulaire de réservation a été ajoutée avec succès', 'Formulaire Réservation', ["positionClass" => "toast-top-right"]);

        }else{
            Toastr::success('Votre formulaire de réservation a été modifié avec succès', 'Formulaire Réservation', ["positionClass" => "toast-top-right"]);

        }
        // return redirect()->route('addReservation', $id) ; 
        return redirect()->back() ;
    }

    public function viewSheetReservation($id)
    {        
        $reserved_sheets_finalstep_side_bar = "reserved_sheets_finalstep_side_bar";   
        $sheet = Sheet::findOrFail($id);       
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;

        return view('app.sheets.viewreservation')
        ->with('sheet', $sheet)         
        ->with('header', $header)         
        ->with('reserved_sheets_finalstep_side_bar', $reserved_sheets_finalstep_side_bar) ;
    }
    public function viewSheetReservationPDF($id)
    {        
        $reserved_sheets_finalstep_side_bar = "reserved_sheets_finalstep_side_bar"; 
        $sheet = Sheet::findOrFail($id);       
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;

        return view('app.sheets.pdfreservation')
        ->with('sheet', $sheet)         
        ->with('header', $header)         
        ->with('reserved_sheets_finalstep_side_bar', $reserved_sheets_finalstep_side_bar) ;
    }

    public function generateSheetReservationPDF($id)
    {
        $sheet = Sheet::findOrFail($id);       
        $sheet_header_id =  $sheet->header;
        $header = Header::findOrFail($sheet_header_id) ;

        $file_name = 'formulaire-reservation-'.$sheet->client_code .'.pdf' ;
        $pdf = PDF::loadView('app.sheets.pdfreservation', [
            'sheet' =>$sheet ,
            'header' =>$header  
        ]);        

        return $pdf->download($file_name);
    }




    public function distributeDeletedSheetToCloser(Request $request)
    { 

        if($request->closer_id == 0){
            Toastr::error('Selectioner un closer !', 'Distribuer les fiches', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }  
        $sheet = Sheet::findOrFail($request->sheet_id);
        $sheet->closer_id = $request->closer_id ;
        $sheet->distributed_at =  Date('Y-m-d');
        $sheet->active = 1 ;
        $sheet->update();         
        Toastr::success('Votre Fiche a été distribué avec succès', 'Distribuer une fiche', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function multiDistributionDeletedSheetsToCloser(Request $request)
    { 
        if($request->closer_id == 0){
            Toastr::error('Selectioner un closer !', 'Distribuer les fiches', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        $sheetsList = $request->sheetsList;
        $sheets_list = explode("," ,$sheetsList);
        //dd($sheets_list); 
        $test = "";
        foreach ($sheets_list as $sheet => $id) { 
            $test .= $id;
            $sheet = Sheet::findOrFail($id);
            $sheet->closer_id = $request->closer_id ;
            $sheet->distributed_at =  Date('Y-m-d');
            $sheet->active = 1 ;
            // $sheet->w_age = 44 ;
            $sheet->update(); 
        }
        Toastr::success('Les fiches ont été distribué avec succès', 'Distribuer les fiches', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function addNewCategory(Request $request)
    {
        $this->validate($request, [
            'title' => 'required' 
        ]);  
        $route =  str_replace(' ', '-', $request->title);
        $category = new Category;
        $category->title = $request->title;
        $category->route = $route; 
        $category->save();
        Toastr::success('Libellé a été crée avec succès', 'Ajouter un  libellé', ["positionClass" => "toast-top-right"]);
        return redirect()->back(); 
    }


    public function assignSheetToCategory(Request $request)
    { 
        if($request->category_id == 0){
            Toastr::error('Selectioner un libellé !', 'Attribué une fiche', ["positionClass" => "toast-top-right"]);

        }  
        $sheet_id = $request->sheet_id ;       
        $category_id = $request->category_id ;  
        $sheet_cate = SheetCategory::where('sheet_id', $sheet_id)->first();

        if($sheet_cate != null){ 
            //update
            $sheet_category = SheetCategory::findOrFail($sheet_cate->id); 
            $sheet_category->category_id = $category_id; 
            $sheet_category->update();
            Toastr::success('Votre Fiche a été attribué avec succès-update', 'Attribué une fiche', ["positionClass" => "toast-top-right"]);

        } else {
            // new
            $sheet_category = new SheetCategory;
            $sheet_category->sheet_id = $sheet_id;
            $sheet_category->category_id = $category_id;
            $sheet_category->save();  
            Toastr::success('Votre Fiche a été attribué avec succès-addNew', 'Attribué une fiche', ["positionClass" => "toast-top-right"]);

        }    
        return redirect()->back(); 
    }
    
    public function assignMultiSheetToCategory(Request $request)
    {
        if($request->category_id == 0){
            Toastr::error('Veuillez selectioner un libellé !', 'Multi Déplacement', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        $sheetsList = $request->sheetsList;
        $sheets_list = explode("," ,$sheetsList); 
        $category_id = $request->category_id;
        foreach ($sheets_list as $sheet => $id) {           
            $sheet_cate = SheetCategory::where('sheet_id', $id)->first();
            if($sheet_cate != null){
                // update
                $sheet_category = SheetCategory::findOrFail($sheet_cate->id); 
                $sheet_category->category_id = $category_id; 
                $sheet_category->update();
            } else {
                // new
                $sheet_category = new SheetCategory;
                $sheet_category->sheet_id = $id;
                $sheet_category->category_id = $category_id;  
                $sheet_category->save(); 
            }
        }
        Toastr::success('Les fiches ont été attribué avec succès', 'Multi Déplacement', ["positionClass" => "toast-top-right"]);

        return redirect()->back();
    }

    public function checkSheetHasCategory($id)
    {
        $sheet = SheetCategory::where('sheet_id', $id)->first();
        if($sheet != null){
            return $sheet->category_id ;
        } else {
            return 0;
        }
    }


    public function getAllSheetPage()
    {     
        $all_sheet_side_bar = "all_sheet_side_bar";  
        $all_status = Status::all();
        $users_closer = User::role('Closer')->get();     
        $statusId ='x';
        $created_at ='';  


        return view('app.sheets.allsheets')
        ->with('all_sheet_side_bar',$all_sheet_side_bar)
        ->with('all_status',$all_status)
        ->with('users_closer',$users_closer)
        ->with('statusId',$statusId)
        ->with('created_at',$created_at);
    }

    public function getAllSheetData()
    { 
        $sheets = DB::table('sheets')
                ->join('users', 'users.id', '=', 'sheets.created_by') 
                ->join('status', 'status.id', '=', 'sheets.status')  
                ->where('sheets.active', 1) 
                ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                ->orderBy('sheets.id', 'asc')
                ->get();  
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';

                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" '; 
                    if( $sheet->closer_id != 0){
                        $show .=  ' data-title="redistribuer">'; 
                        $show .=  ' <span class="ti-wand"></span>';
                    }  else{
                        $show .=  ' data-title="Distribuer">'; 
                        $show .=  ' <span class="ti-settings"></span>';
                    }     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }                    

                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status','multi_selection', 'action', 'created_at'])   
                ->make(true);
    }

    public function getAllSheetFilterPage(Request $request)
    {     
        $all_sheet_side_bar = "all_sheet_side_bar";  
        $all_status = Status::all();
        $users_closer = User::role('Closer')->get(); 
        $statusId = 'x';
        if($request->status_id != null) {
            $statusId = $request->status_id ;
        }else{
            $statusId = 'x';
        }
        $created_at = 'x' ;  
        if($request->created_at != null) {
            $created_at = $request->created_at ;
        }else{
            $created_at = 'x';
        } 
        return view('app.sheets.allsheets')
        ->with('all_sheet_side_bar',$all_sheet_side_bar)
        ->with('all_status',$all_status)
        ->with('users_closer',$users_closer)
        ->with('statusId',$statusId)
        ->with('created_at',$created_at);
    }

    public function getAllSheetDataFilter($status_id,  $created_at)
    {  
        // dd($status_id .' '. $created_at );

        if($status_id != 'x' && $created_at != 'x'){
            // get sheets by status and date  
             
        $created_at = date("Y-m-d", strtotime($created_at));
            $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status')               
                    ->where('sheets.status', $status_id)
                    ->whereDate('sheets.created_at', $created_at) 
                    ->where('sheets.active', 1)   
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get();                 
            
        }elseif($status_id != 'x' && $created_at === 'x'){
            if($status_id == 0 ){
                // get sheets by status only 
                $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.created_by') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->where('sheets.closer_id', 0)
                        ->where('sheets.active', 1)   
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            }else{
                // get sheets by status only 
                $sheets = DB::table('sheets')
                        ->join('users', 'users.id', '=', 'sheets.created_by') 
                        ->join('status', 'status.id', '=', 'sheets.status') 
                        ->where('sheets.status', $status_id) 
                        ->where('sheets.active', 1)   
                        ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                        ->orderBy('sheets.id', 'asc')
                        ->get(); 
            }
        }elseif($status_id === 'x' && $created_at !='x'){
            //get sheets by date only 
        $created_at = date("Y-m-d", strtotime($created_at));
            $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status') 
                    ->whereDate('sheets.created_at', $created_at) 
                    ->where('sheets.active', 1)   
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get(); 
        }elseif($status_id === 'x' && $created_at ==='x'){ 
            $sheets = DB::table('sheets')
                    ->join('users', 'users.id', '=', 'sheets.created_by') 
                    ->join('status', 'status.id', '=', 'sheets.status')  
                    ->where('sheets.active', 1) 
                    ->select( 'sheets.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),'status.name','status.color','status.class')
                    ->orderBy('sheets.id', 'asc')
                    ->get(); 
        } 
        return DataTables::of($sheets) 
                ->addColumn('order', function ($sheet) { 
                    return  $this->order = $this->order + 1;  
                })
                ->addColumn('client_full_name', function ($sheet) { 
                    return   $sheet->m_first_name . ' ' . $sheet->m_last_name .' - '.$sheet->w_first_name . ' ' . $sheet->w_last_name;
                })
                ->addColumn('status', function ($sheet) {
                    
                    $status ='<div class="text-center">'; 
                    $status .='<span class="label label-sm '.$sheet->color.' ">';
                    $status .=  $sheet->name  ;
                    $status .='</span> '; 
                    $status .='</div>';  
                    return   $status;
                })
                ->addColumn('multi_selection', function ($sheet) {
                    $checkbox = '<div class="text-center">' ;
                    $checkbox .= '<input  name="check"  class="check"  id="sheetToDistribute'.$sheet->id .'" type="checkbox" value="'.$sheet->id .'" > ';
                    $checkbox .=  '</div>';  
                    return   $checkbox;
                })
                ->addColumn('action', function ($sheet) {
                    $show = '<a href="'. route('sheets.show', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-info m-r-50" ';  
                    $show .=  'data-title="Voir">';
                    $show .=  '<i class="ti-eye" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a href="'. route('sheets.edit', $sheet->id).'"' ;
                    $show .=  'class="btn btn-icon btn-warning m-r-50" style="margin-left: 2px;" ';  
                    $show .=  'data-title="Modifier">';
                    $show .=  '<i class="ti-pencil" aria-hidden="true"></i> </a>';
                    
                    $show .= '<a id="openDistributeSheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'. $this->order  .'" ' ; 
                    $show .= ' title="'. $sheet->closer_id .'" ' ; 
                    $show .=  ' class="btn btn-success btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Distribuer">'; 
                    $show .=  ' <span class="ti-settings"></span>';     
                    $show .=  ' </a>';
                    
                    $show .= '<a id="openDeletesheet" ' ;
                    $show .= ' value="'. $sheet->id .'" ' ; 
                    $show .= ' name="'.  $this->order  .'" ' ;  
                    $show .=  ' class="btn btn-danger btn-circle update " style="margin-left: 2px;" ';   
                    $show .=  ' data-title="Supprimer">'; 
                    $show .=  ' <span class="ti-trash"></span>';     
                    $show .=  ' </a>'; 

                    if($sheet->email != ''){
                        $show .= '<a id="openSendEmailToClient" ' ;
                        $show .= ' value="'. $sheet->id .'" ' ; 
                        $show .= ' name="'.  $this->order  .'" ' ;  
                        $show .=  ' class="btn btn-default btn-circle update " style="margin-left: 2px;" ';   
                        $show .=  ' data-title="Envoie d\'email">'; 
                        $show .=  ' <span class="ti-email"></span>';     
                        $show .=  ' </a>';  
                    }  
                    return $show;
                })
                ->editColumn('created_at', function ($sheet) {
                    return $sheet->created_at ? with(new Carbon($sheet->created_at))->format('d/m/Y') : '';
                }) 
                ->rawColumns([ 'order', 'client_full_name', 'status','multi_selection', 'action', 'created_at'])   
                ->make(true);
    }

}
