<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MoonShine\Models\MoonshineUser;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user())
            if ($muser = MoonshineUser::where('email', $user->email)->first())
                if ($muser->moonshineUserRole()->first()->name === 'Admin')
                    return $next($request);
        return response()->json(['message' => 'Пользователь не авторизован.'], 401);
    }

}
