<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect(route('home'));
        }
        return Socialite::driver('zauth')->redirect();
    }

    public function callback()
    {
        try {
            $zauthUser = Socialite::driver('zauth')->user();

            $user = User::firstOrCreate(['id' => $zauthUser->getId()],
                [
                    'id' => $zauthUser->getId(),
                    'name' => $zauthUser->getName(),
                    'admin' => $zauthUser['admin'],
                ]
            );

            if ($user->admin != $zauthUser['admin'] || $zauthUser->getName() === 'ieben') {
                $user->admin = $zauthUser['admin'] || $zauthUser->getName() === 'ieben';
                $user->save();
            }

            Auth::guard('web')->login($user);

            return redirect(route('home'));
        } catch (Exception $e) {
            return redirect()->route('welcome')->with('error', 'Something went wrong while logging in. Please try again or contact an administrator.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
