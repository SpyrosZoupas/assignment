<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register() {
        return view('register');
    }

    public function registerPost(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email'=> 'required|string|email|unique:users',
            'password'=> 'required|string|confirmed',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['address'] = $request->address;
        $data['phone'] = $request->phone;

        $user = User::create($data);

        if(!$user) {
            return redirect(route('register'))->with('error','Registration failed, please try again.');
        }

        return redirect(route('login'))->with('success','Successfully registered!');
    }
}
