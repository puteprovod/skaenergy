<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CodeVerificationRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CodeVerificationController extends Controller
{
    public function __invoke(CodeVerificationRequest $request)
    {
        $data = $request->validated();
        $trueCode = Cache::get($data['email']);
        $user = User::where('email',$data['email']);
        if (!$user){
            return response()->json(['message' => 'Не найден пользователь с таким email'], 406);
        }
        if ($trueCode == $data['code']){
            $user->update([
                'email_verified_at' => Carbon::now()->toDateTimeString()
            ]);
            return response()->json(['success' => 'success'], 200);
        }
        else
        {
            return response()->json(['message' => 'Неверный код подтверждения'], 406);
        }

        // TODO: Implement __invoke() method.
    }
}
