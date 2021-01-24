<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Support\Facades\Mail;
use App\Mail\EmailResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordTowController extends Controller
{
    public function getForgotPasswordPage()
    {
        return view('auth.passwords.email');
    }

    public function checkEmailExist(Request $request)
    { 
        
        $user =  User::whereEmail($request->email)->first();
        if( is_null($user)){
            return redirect()->back()->with('danger', 'We can\'t find a user with this e-mail address.');
        }else{
            
            if( $user->count() <>0){
                
                $user_to = User::findOrFail($user->id);
                //dd($user_to->id);
                if($user_to->reset_pass_token === "") { 
                    //create token
                    $token_0 = str_random(16);
                    $token = uniqid($token_0).''.$user_to->id;                
                    $user_to->reset_pass_token =  $token;
                    $user_to->update();
                    //dd($client_to->id);
                    //send email
                    $data_email = [
                        'to'=> $request->email, 
                        'subject'=> 'Reset Password',
                        'user_name'=>  $user_to->name ,
                        'token'=> $token
                    ];                
                    Mail::to($data_email['to'])->send(new EmailResetPassword($data_email));
                    return redirect()->back()->with('status', 'An email has been sent. Please check your email !');
                }else{ 
                    return redirect()->back()->with('danger', 'An email has been already sent. Please check your email');              
                }
            }else{
                return redirect()->back()->with('danger', 'We can\'t find a user with this e-mail address.');
            }
        }
    }

    public function getResetPasswordPage($token)
    {
        $user = User::where('reset_pass_token', $token)->first();
        if(!is_null($user)){  
            return view('auth.passwords.reset')
            ->with('token', $token); 
        }else{
            return view('auth.passwords.tokenexpired'); 
        }         

    }

    public function resetPassword(Request $request)
    {
       
        $this->validate($request,[    
            'email' => 'email|required',
            'password' => 'required|string|min:6',
            'password' => 'required|string|min:6',   
        ]); 
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;
        if($password != $password_confirmation){
            return redirect()->back()->with('danger', 'Password Incorrect'); 
        }else{
           //dd(Hash::make($request->password_confirmation) );
            $user = User::where('reset_pass_token', $request->token)->first();
            if($request->email === $user->email){
                $user->reset_pass_token = '';
                $user->password = Hash::make($request->password_confirmation) ;
                $user->update(); 
                return redirect()->route('login')->with('status', 'Password reset successfully !'); 
            }else{
                return redirect()->back()->with('danger', 'Email Incorrect'); 
            } 
                          
        }

    }




}
