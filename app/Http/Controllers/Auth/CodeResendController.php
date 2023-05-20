<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\User\CodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CodeResendController extends Controller
{
    public function __invoke(User $user = null)
    {
        if ($user){
            Mail::to($user->email)->send(new CodeMail($user->makeNewVerificationCode()));
            return response()->json(['success' => 'success'], 200);
        }
        else{
            return response()->json(['message' => 'Не найден пользователь с таким id'], 406);
        }
    }
}
