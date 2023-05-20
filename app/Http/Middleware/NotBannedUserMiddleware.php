<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class NotBannedUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $blocked_ips = Setting::find(1)->blocked_ips->where('ip',$request->getHttpHost());
        $user = $request->user();

        if ($user->banned_until > Carbon::now()->toDateString())
            return response()->json(['message' => 'Вам ограничена возможность писать сообщения в гостевую книгу до '.$user->banned_until ], 403);
        if ($blocked_ips->isNotEmpty()){
            $blocked_ip_dates = $blocked_ips->where('finish_date','>',Carbon::now()->toDateString())->pluck('finish_date')->sortDesc();
            if ($blocked_ip_dates->isNotEmpty())
                return response()->json(['message' => 'Вашему IP ограничена возможность писать сообщения в гостевую книгу до '.$blocked_ip_dates->first() ], 403);
        }

        return $next($request);
    }
}
