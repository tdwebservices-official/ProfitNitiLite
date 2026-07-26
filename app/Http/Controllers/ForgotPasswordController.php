<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\NewPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Hash;
use Validator;


class ForgotPasswordController extends Controller
{
    /* Display Forgot Password Page */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle "forgot password" request: generate a new random password,
     * save it (hashed) against the user, and email the new plain-text
     * password to them.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ]);

        if ($validator->fails()) {
            return redirect()->to('forgot-password')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->get('email'))->first();

        // Always show the same message, whether or not the email exists,
        // so we don't leak which addresses are registered.
        $message = 'If an account exists for that email, a new password has been sent to it.';

        if ($user != null) {
            try {
                $newPassword = Str::random(10);
    
                $user->password = $newPassword;
                $user->save();
    
                Mail::to($user->email)->send(
                    new NewPasswordMail($user, $newPassword)
                );
    
            } catch (\Exception $e) {
                
                return redirect()->to('forgot-password')
                ->withInput($request->only('email'))
                ->with('error', 'Unable to send the email at the moment. Please try again later.');
            }
        }

        return redirect()->to('forgot-password')->with('success', $message);
    }
}