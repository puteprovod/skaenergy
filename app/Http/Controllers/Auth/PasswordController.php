<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);
        $user = $request->user();
        $editedPasswordHash = Hash::make(saltPassword($validated['current_password'],$user->email));
        if ($user->password === $editedPasswordHash) {
            $editedNewPassword = saltPassword($validated['password'],$user->email);
            $request->user()->update([
                'password' => Hash::make($editedNewPassword),
            ]);
            return response()->json(['success' => 'success'], 200);
        }
        return response()->json(['errors' => [ 'current_password' => [ 0 => 'Неверно указан текущий пароль']]], 406);
    }
}
