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
                ]
            );

            // TODO: update admin status on login.

            Auth::guard('web')->login($user);

            return redirect('/');
        } catch (Exception $e) {
            return redirect(route('login'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
