<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use MoonShine\Models\MoonshineUser;

class TestController extends Controller
{
    public function __invoke()
    {
       $user = User::find(5)->email;
      $muser = MoonshineUser::where('email',$user)->first();
       dump ($muser->moonshineUserRole->name);
    }
}
