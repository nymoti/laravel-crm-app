<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recall;
use DB;
use Carbon\Carbon;
class RecallController extends Controller
{



    public function __construct() {        

        parent::__construct();
    }
    public function index()
    {        
        $calender_side_bar = "calender_side_bar"; 
        return view('app.calender.index')
        ->with('calender_side_bar', $calender_side_bar);
    } 
    public function getRecall()
    {   
        $recalls = DB::table('recalls')->select('id', 'full_name as title', 'start_date as start', 'color as backgroundColor' ,'address' ,'tel','hour' ,'description')->where('created_by',Auth()->user()->id)->get();
        return response()->json($recalls);
    }  

    public function store(Request $request)
    { 
        // dd($request->all());
        $recall = new Recall;
        $recall->created_by = $request->created_by;
        $recall->full_name = $request->full_name;
        $recall->address = $request->address;
        $recall->tel = $request->tel;
        $recall->start_date = $request->start_date;
        $recall->hour = $request->hour;
        $recall->color = $request->color;
        $recall->description = $request->description;
        $recall->save(); 

        return response()->json($recall,200); 

    } 
    public function updateRecall(Request $request)
    { 
        // dd($request->all()) ;
        $recall = Recall::findOrFail($request->id);
        $incomeDate = Carbon::parse($request->start_date)->format('Y-d-m') ;
        $realDate = Carbon::parse($recall->start_date)->format('Y-d-m') ; 
       
        // if($realDate != $incomeDate ) {
        //     dd('date not equal');
        // }else{
        //     dd('date is equal');

        // }
        $recall->full_name = $request->full_name;
        $recall->address = $request->address;
        $recall->tel = $request->tel;
        $recall->start_date = $request->start_date;
        $recall->hour = $request->hour; 
        $recall->description = $request->description;         
        $recall->color = $request->color;
        $recall->update(); 
        return response()->json($recall,200); 

    } 

    public function deleteRecall($id)
    { 
        $recall = Recall::findOrFail($id); 
        $recall->delete();  
        return response()->json($recall,200); 

    }
}
