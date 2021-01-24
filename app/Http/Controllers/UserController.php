<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Auth;
use DB;
use Hash;
use Toastr;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class UserController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
       // $this->middleware(['role:Admin','permission:user-list']);
        parent::__construct();
        $this->middleware('permission:user-list');

        $this->middleware('permission:user-create', ['only' => ['create','store']]);

        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:user-delete', ['only' => ['destroy']]); 
    }
 
    public function index() {  
        $users_admin = User::role('Admin')->where('active', 1)->get();
        $users_agent = User::role('Agent')->where('active', 1)->get();
        $users_closer = User::role('Closer')->where('active', 1)->get();  
        $loged_id = Auth()->user()->id; 
        //remove loged id from the  users list
        foreach($users_admin as $elementKey => $element) { 
            if($element->id ==  $loged_id){
                unset($users_admin[$elementKey]);
            }             
        }          
        $users_side_bar = "users_side_bar";
        $users_side_bar_list = "users_side_bar_list"; 

        return view('app.users.index')
        ->with('users_admin', $users_admin)
        ->with('users_agent', $users_agent)
        ->with('users_closer', $users_closer)
        ->with('users_side_bar', $users_side_bar)
        ->with('users_side_bar_list', $users_side_bar_list);  
    }
 

    
    public function getDeletedUsers() {  
        $users_admin = User::role('Admin')->where('active', 0)->get();
        $users_agent = User::role('Agent')->where('active', 0)->get();
        $users_closer = User::role('Closer')->where('active', 0)->get(); 
        $loged_id = Auth()->user()->id; 
        //remove loged id from the  users list
        foreach($users_admin as $elementKey => $element) { 
            if($element->id ==  $loged_id){
                unset($users_admin[$elementKey]);
            }             
        }          
        $users_side_bar = "users_side_bar";
        $deleted_users_side_bar_list = "deleted_users_side_bar_list"; 

        return view('app.users.deletedusers')
        ->with('users_admin', $users_admin)
        ->with('users_agent', $users_agent)
        ->with('users_closer', $users_closer)
        ->with('users_side_bar', $users_side_bar)
        ->with('deleted_users_side_bar_list', $deleted_users_side_bar_list);  
    }

    
    public function create() {
        $roles = Role::pluck('name','name')->all();
        
        $users_side_bar = "users_side_bar";
        $users_side_bar_newone = "users_side_bar_newone"; 
        return view('app.users.create')
        ->with('roles', $roles)
        ->with('users_side_bar', $users_side_bar)
        ->with('users_side_bar_newone', $users_side_bar_newone);  
    }
 
    public function store(Request $request) 
    {
        // dd($request->all());
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|same:confirm-password',
            'role' => 'required', 
            'user_etat' => 'required'
        ]);
        $generatedEmail =  strtolower($request->fixe_tmk) .'-travels@books.com' ;
        // dd($generatedEmail);
        //'email' => 'required|email|unique:users,email',
        $user = new User;
        $user->first_name =  $request->first_name ;
        $user->last_name =  $request->last_name ;
        $user->email =  $generatedEmail ;
        $user->password =  $request->password;
        $user->fixe_tmk =  $request->fixe_tmk;
        $user->active =  $request->user_etat;
        $user->save();
        $user->assignRole($request->role);
 
        Toastr::success('Utilisateur a étè crée avec succès', 'Ajouter un  utilisateur', ["positionClass" => "toast-top-right"]);
        return redirect()->route('users.index');  
    }
 
    public function show($id) {
        $user = User::find($id);

        return view('app.users.show',compact('user'));
    }
 
    public function edit($id) {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all(); 
        $hasPermissionTo =   $user->can('send-email');;
        // $userRole ;
        // if($user->hasRole('Admin')){           
        //      $userRole = 'Admin';
        // }elseif ($user->hasRole('Agent')){          
        //     $userRole = 'Agent';
        // }elseif ($user->hasRole('Closer')) {           
        //     $userRole = 'Closer';
        // }
        $users_side_bar = "users_side_bar";
        return view('app.users.edit',compact('user','roles','userRole','users_side_bar','hasPermissionTo'));

    }

     
    public function update(Request $request, $id) 
    {
        // dd($request->all()); 


        if(!empty($request->password)){
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required', 
                'password' => 'required|same:confirm-password', 
                'role' => 'required', 
                'user_etat' => 'required', 
            ]);
            $user = User::find($id);
            $user->first_name =  $request->first_name ;
            $user->last_name =  $request->last_name ; 
            $user->password =  $request->password;
            $user->fixe_tmk =  $request->fixe_tmk;
            $user->active =  $request->user_etat;            
            if($request->allowCloserToSendEmail === "on") { 
                $user->givePermissionTo('send-email');
            }else{ 
                $user->revokePermissionTo('send-email'); 
            }
            $user->update();
        }else {
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required', 
                'role' => 'required', 
                'user_etat' => 'required', 
            ]);
            $user = User::find($id);
            $user->first_name =  $request->first_name ;
            $user->last_name =  $request->last_name ; 
            $user->fixe_tmk =  $request->fixe_tmk;
            $user->active =  $request->user_etat;            
            if($request->allowCloserToSendEmail === "on") { 
                $user->givePermissionTo('send-email');
            }else{ 
                $user->revokePermissionTo('send-email'); 
            }
            $user->update();
        } 
        DB::table('model_has_roles')->where('model_id',$id)->delete();         
        $user->assignRole($request->role); 
        Toastr::success('Utilisateur a étè modifié avec succès', 'Modifier un utilisateur', ["positionClass" => "toast-top-right"]);
        return redirect()->route('users.index');       
                 
    } 

    public function destroy($id)
    {
        User::find($id)->delete();
        Toastr::success('Utilisateur a étè supprimé avec succès', 'Supprimer un utilisateur', ["positionClass" => "toast-top-right"]);
        return redirect()->route('users.index'); 
    }

    public function getAllUsers()
    {        
        $users = User::all();
        return response()->json([
            'users'    => $users
        ], 200);
    }

    public function userInfos($id)
    {        
        $user = User::findOrFail($id); 
        return response()->json([
            'user'    => $user
        ], 200);
    }
    public function deactivateUserAccount(Request $request)
    {
        $user = User::findOrFail($request->id);
        if($request->active == 1 ){
            $user->active = 1;
            $user->update();
        } elseif($request->active == 0){
            $user->active = 0;
            $user->update();
        }
        return response()->json([
            'user'    => $user,
            'active'    => $request->active
        ], 200);
    }

    public function getProfilePage(){        
        $loged_id = Auth()->user()->id; 
        return view('app.users.profile');
    }    

    public function updateProfile(Request $request, $id) 
    {
        if(!empty($request->password)){ 
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required', 
                'password' => 'required|same:confirm-password'  
            ]);
            $user = User::find($id);
            $user->first_name =  $request->first_name ;
            $user->last_name =  $request->last_name ;  
            $user->password =  $request->password;
            $user->fixe_tmk =  $request->fixe_tmk; 
            $user->update();
        }else {            
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required'   
            ]);
            $user = User::find($id);
            $user->first_name =  $request->first_name ;
            $user->last_name =  $request->last_name ; 
            $user->fixe_tmk =  $request->fixe_tmk; 
            $user->update();
        }  
        Toastr::success('Votre profile a étè modifié avec succès', 'Modification de profile', ["positionClass" => "toast-top-right"]);
        return redirect()->route('getProfilePage');       
                 
    } 

}