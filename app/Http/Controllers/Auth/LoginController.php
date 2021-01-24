<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $fixe_tmk;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
 
        $this->fixe_tmk = $this->findUsername();
    }

     /**
     * Get the login fixe_tmk to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login');
 
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'fixe_tmk';
 
        request()->merge([$fieldType => $login]);
 
        return $fieldType;
    }
 
    /**
     * Get fixe_tmk property.
     *
     * @return string
     */
    public function username()
    {
        return $this->fixe_tmk;
    }
}
