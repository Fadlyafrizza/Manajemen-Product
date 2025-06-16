<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Product\ProductCollection;

class AuthController extends Controller
{
    public function login()
    {
        return inertia('auth/Login');
    }

    public function register()
    {
        return inertia('auth/Register');
    }


    public function login_post(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials, $request->remember)) {

            $request->session()->regenerate();

            if(auth()->user()->role === 'admin') return to_route('pages.dashboard');

            return to_route('pages.home');

        } else {
            return back()->withErrors([
                'email' => 'Email atau Password Salah'
            ])->onlyInput('email');
        }
    }

    public function register_post(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'avatar'=> 'nullable|image',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);


        if($credentials->fails()) {
            return back()->withErrors($credentials->errors());
        }

        DB::transaction(function () use ($request) {

            if(User::where('email', $request->email)->exists()) throw new \Exception('Email has been registered');

            $users = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
                'created_at' => now()
            ]);

            Auth::login($users);
        });

        return to_route('pages.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->intended('/login');
    }

    public function test()
    {
        return response()->json(
           [
            'user' =>  User::get(),
            'product' => new ProductCollection(Product::paginate(5))
           ]
        );
    }
}
