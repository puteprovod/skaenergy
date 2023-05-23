<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function callbackGoogle(){
        $user = Socialite::driver('google')->stateless()->user();
        $this->regOrLogin($user);
        return redirect('/?social=1'); ///
    }
    public function regOrLogin($googleUser) {
        $user = User::where('email',$googleUser->email)->first();
        if (!$user){
            while (User::where('nick',$googleUser->name)->count()>0){
                $randomInt = strval(random_int(1, 9));
                $googleUser->name.=$randomInt;
            }
            $user = User::create([
                'nick' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(20)),
                'socialite_key' => $googleUser->picture,
            ]);
        }
        Auth::login($user);
    }
}
