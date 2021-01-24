<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DateTime;
use App\Sheet;
use App\User;
use App\Status;
use App\Info;
use DB;
use Session;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');        

        parent::__construct();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    // date_default_timezone_set('Europe/Stockholm');
    public function checkDayAndTime($dateTime)
    {       
        // $date = date_default_timezone_set ('Africa/Casablanca') ;
        // dd($date);
        $info = Info::where('id',1)->first();
        $time_start = $info->time_start;
        $time_end = $info->time_end;
        $weekdayStartTime = new DateTime($time_start);
        $weekdayEndTime = new DateTime($time_end);  
        $dayOfTheWeek = intval($dateTime->format('N'));  
        // On a weekday 
        $state ='';
        if($info->weekend != 1){
            // weekend is not included in controle
            if ($dayOfTheWeek < 6) {
                $isOpen = $dateTime > $weekdayStartTime && $dateTime < $weekdayEndTime;
                // dd($isOpen); 
                if (!$isOpen) { 
                    $state = 'time-error';
                    // dd('we are close');
                    // // rederect to error page with msg + times details
                    // dd($isOpen); 
                }else{
                    $state =  'time-success';
                    // dd('we are oppen');
                    // dd($isOpen);
                    // rederect to home page
                }
            }else{
                $state = 'day-error';
                // dd('false');
                // rederect to error page with msg +day and times details
            }
        }else{
            // weekend is included in controle
            if ($dayOfTheWeek <= 6) {
                $isOpen = $dateTime > $weekdayStartTime && $dateTime < $weekdayEndTime;
                // dd($isOpen); 
                if (!$isOpen) { 
                    $state = 'time-error';
                    // dd('we are close');
                    // // rederect to error page with msg + times details
                    // dd($isOpen); 
                }else{
                    $state =  'time-success';
                    // dd('we are oppen');
                    // dd($isOpen);
                    // rederect to home page
                } 
            }else{
                $state = 'day-error';
                // dd('false');
                // rederect to error page with msg +day and times details
            }
        }
        //return 'time-success';
        return $state;

    }

      

    public function index()
    {
      
        $countAllSheets = Sheet::where('active', 1)->count(); 
        $countNewNonDistributedSheets = Sheet::where('status', 0)->orderBy('created_at','desc')->where('active', 1)->take(5)->get(); 
        // dd($countNewNonDistributedSheets);
        $countReservedSheets = Sheet::where('status', 4)->where('active', 1)->count(); 
        $countRecallSheets = Sheet::where('status', 3)->where('active', 1)->count(); 
        $countActiveAgents = User::role('Agent')->where('active', 1)->count(); 

        $sheetNRP = Sheet::where('closer_id', Auth()->user()->id)->where('status', 1)->orderBy('created_at','desc')->where('active', 1)->take(3)->get();
        $sheetRefus = Sheet::where('closer_id', Auth()->user()->id)->where('status', 2)->orderBy('created_at','desc')->where('active', 1)->take(3)->get();
        $sheetRecalls = Sheet::where('closer_id', Auth()->user()->id)->where('status', 3)->orderBy('created_at','desc')->where('active', 1)->take(3)->get();
        $allSatatus = Status::all();
 
        $sheetRecallsRefusNRP = new collection(array_merge($sheetNRP->toArray(),$sheetRefus->toArray(),$sheetRecalls->toArray() ));
        // $sheetRecallsRefusNRP = array_merge($sheetNRP,$sheetRefus,$sheetRecalls );
         
        // dd($sheetRecallsRefusNRP);
        // dd($sheetRecallsRefusNRP);
        // $sheetRecallsRefusNRP = DB::table('sheets')
        //         ->where('closer_id','=', 11)
        //         ->orwhere('status', 1)
        //         ->orwhere('status', 2)
        //         ->orwhere('status', 3)
        //         ->orderBy('created_at','desc')
        //         ->take(3)
        //         ->get();
        // dd($sheetRecallsRefusNRP);
 
        $sheetLastDistributedByAdmin = Sheet::where('closer_id', Auth()->user()->id)->where('status', 0)->orderBy('created_at','desc')->where('active', 1)->take(10)->get();

        $statistic_all_agents =  User::role('Agent')->get();
        $countPerAgent = array();
        $nameAgent = [];
        foreach ($statistic_all_agents as $agent) {
                $countAgentSheets = Sheet::where('created_by', $agent->id)->count();
                $full_name = $agent->first_name .' ' . $agent->last_name ;
                 array_push($countPerAgent,  $countAgentSheets  );
                 array_push($nameAgent,  $full_name  );
        }

        $dashboard = "dashboard";
        // $statistic_all_agents =  User::role('Agent')->where('active', 1)->get();
        //  
        $state =$this->checkDayAndTime(new DateTime()); 
        if (Auth()->user()->hasRole('Admin')){ 
            if($state === 'time-error' || $state === 'day-error' ){ 
                // dd($state) ;
                // Session::flush(); 
                 Auth::logout();
                return redirect('/login')->with('status', 'Vous ne pouvez pas Se connecter a votre espace d\'administration !.');    
            } 
        }
        if (Auth()->user()->hasRole('Agent')){   
            if($state === 'time-error' || $state === 'day-error' ){  
                // dd($state) ;
                // Session::flush();
                Auth::logout();
                return redirect('/login')->with('status', 'Vous ne pouvez pas Se connecter a votre espace d\'administration !.');    
            }else{ 
                return redirect()->route('sheets.index');
            } 
        }
        if (Auth()->user()->hasRole('Closer')){  
            if($state === 'time-error' || $state === 'day-error' ){    
                // dd($state) ;
                Auth::logout();
                Session::flush();return redirect('/login')->with('status', 'Vous ne pouvez pas Se connecter a votre espace d\'administration !.');     
            } 
        }

        return view('app.home')
        ->with('countAllSheets',$countAllSheets)  
        ->with('allSatatus',$allSatatus) 
        ->with('sheetLastDistributedByAdmin',$sheetLastDistributedByAdmin)
        ->with('sheetRecallsRefusNRP',$sheetRecallsRefusNRP)
        ->with('countReservedSheets',$countReservedSheets)
        ->with('countRecallSheets',$countRecallSheets)
        ->with('countActiveAgents',$countActiveAgents)
        ->with('countNewNonDistributedSheets',$countNewNonDistributedSheets)
        ->with('dashboard',$dashboard);
    
    }

     


}
