<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Header;
use Toastr;
use File;
class HeaderController extends Controller
{
    

    public function createHeader(Request $request)
    { 
        // dd($request->all());

        $this->validate($request, [
            'title' => 'required',
            'site' => 'required',
            'email' => 'required',
            'tel' => 'required'
        ]);
        
		$header = new Header;
        $set_default = 0 ;
        if($request->has('set_default')){
            $set_default = $request->set_default;
        }else{            
            $set_default = 0 ;
        }
		$logo = 'heder_default.png' ;
		// dd($old_logo);
		$new_image = $logo;
        if ($request->hasFile('logo')) {
          
            $image = $request->file('logo'); 
            $name = str_slug($request->title).'_'.time() .'.'.$image->getClientOriginalExtension();
            // $destinationPath = public_path('/uploads/admin');
            $destinationPath = base_path().'/../uploads/admin'; 

            $imagePath = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name); 
            //delete Old image
            File::delete($destinationPath .  "/". $logo);
            $new_image = $name;
        }
     //    dd($new_image);
    	// dd($request->all()); 
        $default_header = Header::where('set_default', 1)->first(); 
        if( $default_header != null && $request->has('set_default')){
            $default_header->set_default = 0 ;
            $default_header->update();
        }
		$header->title = $request->title;
		$header->email = $request->email;
		$header->site = $request->site;
		$header->tel = $request->tel;
        $header->logo = $new_image;
		$header->set_default = $set_default;
		$header->save();

        // set old default header to 0
        Toastr::success('Votre Entête a étè ajouté avec succès', 'Ajouter Entête', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getInfosPage'); 
    }

    public function editHeader($id)
    {
    	$infos_side_bar = 'infos_side_bar';
		$header = Header::findOrFail($id);
        $other_headers = Header::where('id','!=', $id)->get();
 
		return view('app.info.editheader')
        ->with('header', $header)
		->with('other_headers', $other_headers)
		->with('infos_side_bar', $infos_side_bar);
    }


	public function updateHeader(Request $request)
	{
        // dd($request->set_default);
        // dd($request->all());

        $this->validate($request, [
            'title' => 'required',
            'site' => 'required',
            'email' => 'required',
            'tel' => 'required'
        ]);
        
		$header = Header::findOrFail($request->headerId);
        if($header->set_default != 0 && $request->set_default === null &&  $request->newDefaultHeader == 0){
            Toastr::error('PLZ SELECT A HEADER', 'Modifier Entête', ["positionClass" => "toast-top-right"]);
            return redirect()->route('editHeader', $request->headerId );
        } 
         
		// dd($request->file('logo'));
		$old_logo = $header->logo ;
		// dd($old_logo);
		$new_image = $old_logo;
        if ($request->hasFile('logo')) {
          
            $image = $request->file('logo'); 
            $name = str_slug($request->title).'_'.time() .'.'.$image->getClientOriginalExtension();
            // $destinationPath = public_path('/uploads/admin');//base_path().'/../public_html/uploads/rooms'; 

            $destinationPath = base_path().'/../uploads/admin'; 

            $imagePath = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name); 
            //delete Old image
            // if($old_logo != "heder_default.png"){
            //     File::delete($destinationPath .  "/". $old_logo); 
            // }

            $new_image = $name;
        }
     //    dd($new_image);
    	// dd($request->all()); 
		$header->title = $request->title;
		$header->email = $request->email;
		$header->site = $request->site;
		$header->tel = $request->tel;
		$header->logo = $new_image;
		$header->update(); 
        if($header->set_default != 0 ){ // set_default = 1
            if($request->set_default === null &&  $request->newDefaultHeader != 0){ 
                $header->set_default = 0 ;
                $header->update();
                $new_default_header = Header::findOrFail($request->newDefaultHeader);
                $new_default_header->set_default = 1 ; 
                $new_default_header->update();
            }            
        }else { // set_default = 0 
                $old_default_header = Header::where('set_default', 1)->first();
                // dd($header) ; 
            if($request->set_default != null ){ 
                // dd($header) ; 
                $old_default_header = Header::where('set_default', 1)->first(); 
                $old_default_header->set_default = 0 ; 
                $old_default_header->update();
                $header->set_default = 1 ;
                $header->update();
            } 
        } 

        Toastr::success('Votre Entête a étè modifié avec succès', 'Modifier Entête', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getInfosPage');  
	}

	public function deleteHeader($id)
	{
		$header = Header::findOrFail($id);
        if($header->logo != 'heder_default.png'){ 

            $destinationPath = base_path().'/../uploads/admin'; 

            //delete  image
            File::delete($destinationPath .  "/". $header->logo);
        }
		$header->delete();
        Toastr::success('Votre Entête a étè supprimé avec succès', 'Supprimer Entête', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getInfosPage');  

	}
 
}
