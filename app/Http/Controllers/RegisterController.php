<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Hash;
use Validator;

class RegisterController extends Controller
{
    /* Display Register Page */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle account registration request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'mobile'    => ['required', 'digits:10', 'unique:users,mobile'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->to('register')
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name'     => $request->get('full_name'),
            'username' => $request->get('username'),
            'email'    => $request->get('email'),
            'mobile'    => $request->get('mobile'),
            'password' => $request->get('password'),
        ]);

        Auth::login($user);
        
        // Sync role correctly
        $roleId = 2;
        $role = Role::find($roleId);
        if ($role) {
            $user->syncRoles([$role]); // or $role->name
        }

        return redirect()->intended('/me/edit-profile');
    }
}