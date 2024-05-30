<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    function signin(){
        return view('auth.signin');
    }
    
    function registr(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:App\Models\User|email',
            'password'=>'required|min:6'
        ]);
        //$response =[
           // 'name'=>$response->name,
           // 'email'=>$response->email,
           // 'password'=>$response->password
       // ];
        //return response()->json($response);
        $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        $token= $user->createToken('MyAppTokens');
        if ($request->expextsJson()) return response()->json($token);
        return redirect()->route('login');
    }

    function login(){
        return view('auth.signup');
    }
    function signup(Request $request){
        $credentials = $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        if (Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('/article');
        }
        return back()->withErrors([
            'email'=>'The provided credentials do not math our records',
        ])->onlyInput('email');
    }

    function logout(Request $request){
        auth()->user()->tokens()->delete();
        if ($request->expextsJson()) return response()->json('logout');
        // $session = $request->session()->all();
        // Log::warning($session);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // $session2 = $request->session()->all();
        // Log::warning($session2);
        return redirect('/');
    }
}