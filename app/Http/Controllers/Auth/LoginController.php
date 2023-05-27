<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectVk(){
        return Socialite::driver('vkontakte')->redirect();
    }
    public function callbackVk(){
        $user = Socialite::driver('vkontakte')->user();
        $this->regOrLogin($user);
        return redirect('/?social=1'); //
    }
    public function redirectFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    public function callbackFacebook(){
        $user = Socialite::driver('facebook')->user();
        $this->regOrLogin($user);
        return redirect('/?social=1'); ////
    }
    public function redirectGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(){
        $user = Socialite::driver('google')->stateless()->user();
        $this->regOrLogin($user);
        return redirect('/?social=1'); //
    }
    public function regOrLogin($socialUser) {
        $user = User::where('email',$socialUser->email)->first();
        if (!$user){
            while (User::where('nick',$socialUser->name)->count()>0){
                $randomInt = strval(random_int(1, 9));
                $socialUser->name.=$randomInt;
            }
            $user = User::create([
                'nick' => $socialUser->name,
                'email' => $socialUser->email,
                'password' => Hash::make(Str::random(20)),
                'email_verified_at' => Carbon::now()->toDateTimeString()
            ]);
        }
        Auth::login($user);
    }
}
