<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\User\CodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function store(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data['password'] = saltPassword($data['password'],$data['email']);
        $user = User::create([
            'nick' => $data['nick'],
            'email' => $data['email'],
            'city' => $data['city'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Mail::to($data['email'])->send(new CodeMail($user->makeNewVerificationCode()));

        Auth::login($user);

        return response()->json(['success' => 'success'], 200);

    }
}
