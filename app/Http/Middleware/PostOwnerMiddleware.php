<?php

namespace App\Http\Middleware;

use App\Models\GbookPost;
use Closure;
use Illuminate\Http\Request;
use MoonShine\Models\MoonshineUser;
use Symfony\Component\HttpFoundation\Response;

class PostOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $post =  $request->route('post');
       if ($user = $request->user()) {
           if ($post->user_id === $request->user()->id)
               return $next($request);
           if ($muser = MoonshineUser::where('email', $user->email)->first())
               if ($muser->moonshineUserRole->name === 'Admin' or $muser->moonshineUserRole->name === 'Supervisor')
                   return $next($request);
       }
       return response()->json(['message' => 'Пользователь не авторизован.'], 401);
    }
}
