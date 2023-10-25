<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register() {
        return view('user.register');
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

    public function login() {
        return view('user.login');
    }

    public function loginPost(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials)){
            session(['user_id' => Auth::id()]);

            if (!session()->has('cart')) {
                session(['cart' => []]);
            }
        
            return redirect()->intended(route('home'));
        }

        return redirect(route('login'))->with("error", "Login details incorrect!");
    }

    public function logout() {
        Auth::logout();
        return redirect(route('home'));
    }
}
