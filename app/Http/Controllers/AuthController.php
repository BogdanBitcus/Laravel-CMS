<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->is_admin == 1) {
                return redirect('/cms/dashboard');
            } else {
                Auth::logout();
                return redirect('/cms')->with('error', __('You do not have permission to access this page'));
            }
        }


        return redirect('/cms')->with('error', __('Wrong email or password'));
    }



    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/cms');
    }
}
