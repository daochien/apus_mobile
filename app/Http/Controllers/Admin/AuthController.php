<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * view login
     */
    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('admin.auth.login');
    }

    /**
     * sign in
     */
    public function signIn(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->with('message.error', 'Login fail');
        }

        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            return redirect()->route('admin.dashboard.index')->with('message.success', 'Welcome');
        }

        return redirect()->back()->with('message.error', 'Login fail');
    }

    /**
     * logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

}
