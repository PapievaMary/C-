<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models;
class AuthController extends Controller
{
    function signin(){
        return view('auth.signin');
    }
    
    function registr(Request $request){
        $request()->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required|mion:6'
        ]);
        $response =[
            'name'=>$response->name,
            'email'=>$response->email,
            'password'=>$response->password
        ];
        //return response()->json($response);

        User::crete([
            'name'=>$response->name,
            'email'=>$response->email,
            'password'=>Hash::make($response->password),
        ]);
        return redirect('/');

    }
}