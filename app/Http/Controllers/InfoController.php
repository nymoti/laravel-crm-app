<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Info;
use App\Header;
use Toastr;
use File;
class InfoController extends Controller
{
    

    public function __construct() {        

        parent::__construct();
    }
    
	public function getInfosPage()
	{
		$infos_side_bar = 'infos_side_bar';
		$headers = Header::all();
		return view('app.info.index')
		->with('headers', $headers)
		->with('infos_side_bar', $infos_side_bar);
	}

	public function getSettingPage()
	{
		$setting_side_bar = 'setting_side_bar';  
        $info = Info::where('id',1)->first();
		return view('app.info.setting') 
		->with('info', $info)
		->with('setting_side_bar', $setting_side_bar);
	}


	public function updateControleAccess(Request $request)
	{
		// dd($request->all());
		$info = Info::findOrFail(1); 
		$info->time_start = $request->time_start;
		$info->time_end = $request->time_end;   
		$info->weekend = $request->weekend;  
		$info->update();

        Toastr::success('Votre Contrôle d accès a étè modifié avec succès', 'Modifier Contrôle d accès', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getSettingPage');  
	}


	public function updateInfos(Request $request)
	{
		// dd($request->all());

		$info = Info::findOrFail($request->info_id); 

		$info->title = $request->title;
		$info->email = $request->email;
		$info->site = $request->site;
		$info->tel = $request->tel; 
		$info->update();

        Toastr::success('Votre Infos a étè modifié avec succès', 'Modifier info general', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getInfosPage');  
	}




	public function xupdateInfos(Request $request)
	{
		// dd($request->all());

		$info = Info::findOrFail($request->info_id);
		$old_logo = $info->logo ;
		// dd($old_logo);
		$agency_image = $old_logo;
        if ($request->hasFile('logo')) {
          
            $image = $request->file('logo'); 
            $name = str_slug($request->title).'_'.time() .'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/admin');//base_path().'/../public_html/uploads/rooms'; 
            $imagePath = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name); 
            //delete Old image
            File::delete($destinationPath .  "/". $old_logo);
            $agency_image = $name;
        }

		$info->title = $request->title;
		$info->email = $request->email;
		$info->site = $request->site;
		$info->tel = $request->tel;
		$info->logo = $agency_image;
		$info->update();

        Toastr::success('Votre Infos a étè modifié avec succès', 'Modifier info general', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getInfosPage');  
	}
}
