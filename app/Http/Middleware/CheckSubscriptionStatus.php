<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_subscribed && $user->subscribed_until && $user->subscribed_until->lt(now())) {
           
            $user->update([
                'is_subscribed' => false,
                'subscribed_until' => null,
            ]);
        }

        return $next($request);
    }
}
