<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class RegisteredUserController extends Controller
{
    public function create(){
        return view('auth.user-register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'cellphone' => 'required|regex:/^[0-9]{10}$/',
            'sex' => [
                'required',
                Rule::in(User::USER_SEX_CHOICES),
            ],
            'password' => 'required|string|confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'user_type' => [
                'required',
                Rule::in(User::PUBLIC_USER_TYPE_CHOICES),
            ],
        ], [
            'password.regex' => 'La contraseña no cumple con los requisitos, al menos una letra y al menos un número.'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cellphone' => $request->cellphone,
            'sex' => $request->sex,
            'user_type' => $request->user_type,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::LANDING);
    }
}
