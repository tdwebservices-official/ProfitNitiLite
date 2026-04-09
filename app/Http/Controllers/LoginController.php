<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\Login\RememberMeExpiration;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Session;
use Hash;
use URL;
use Validator;

class LoginController extends Controller{

     use RememberMeExpiration;
    
    /* Display Login Page */
    public function show(){        
        return view('auth.login');
    }

    /**
    * Handle account login request
    * 
    * @param LoginRequest $request
    * 
    * @return \Illuminate\Http\Response
    */
    public function login(LoginRequest $request)
    {   
        $credentials = $request->getCredentials();


        if(!Auth::validate($credentials)):
            return redirect()->to('login')
            ->withErrors(trans('auth.failed'));
        endif;
        $user_data = '';
        if( isset($credentials['username']) && $credentials['username'] != '' && $credentials['username'] != null ){
            $user_data = User::where('username',$credentials['username'])->first();
        }

        if( isset($credentials['email']) && $credentials['email'] != '' & $credentials['email'] != null ){
            $user_data = User::where('email',$credentials['email'])->first();

        }
        if($user_data != null && Hash::check($request->get('password'), $user_data->password) == true){           
            $credentials = [
                'email' => $user_data->email,
                'password' => $user_data->password,
            ];
            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            Auth::login($user, $request->get('remember'));
            if($request->get('remember')):
                $this->setRememberMeExpiration($user);
            endif;
            return $this->authenticated($request, $user);

        }else{
            return redirect()->to('login')->withErrors(trans('auth.failed'));
        }
        return redirect()->to('login')->withErrors(trans('auth.failed'));
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended();
    }

    // User logout
    public function logout(Request $request)
    {
        Auth::logout();
       return redirect('login');
   }

}