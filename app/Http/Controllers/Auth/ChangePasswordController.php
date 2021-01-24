<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
// use Hash;
use Validator;
use Toastr;
class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    protected $redirectTo =  '/admin/home';

    public function showChangePasswordForm()
    {
        $user = Auth::getUser();
        $RiadNotifications = \App::call('App\Http\Controllers\Admin\HomeController@notifyRiad');

        return view('auth.change_password', compact('user','RiadNotifications'));
    }

    public function changePassword(Request $request)
    {
        $user = Auth::getUser();
        $newUser = $request->get('name');
        $newEmail = $request->get('email');
        $newPassword = $request->get('new_password');
        if(isset($newUser))
        {
            $user->name = $newUser;
        }
        if(isset($newEmail))
        {
            $user->email = $newEmail;
        }
        if(isset($newPassword))
        {
            $user->password = bcrypt($newPassword);
        }         
        $user->update();
        Toastr::success('Vos infos sont modifié avec succès !' , 'success', ["positionClass" => "toast-top-center"]);
        return redirect($this->redirectTo)->with('success', 'Password change successfully!');
        
    }
 
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'new_password' => 'required|string|min:6',
        ]);
    }
    
}
