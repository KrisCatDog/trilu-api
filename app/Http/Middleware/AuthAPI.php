<?php

namespace App\Http\Middleware;

use App\Models\LoginToken;
use Closure;
use Illuminate\Http\Request;

class AuthAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!LoginToken::where('token', $request->token)->first()) {
            return response()->json(['message' => 'unauthorized user']);
        } else {
            $user = LoginToken::where('token', $request->token)->first()->user;

            auth()->login($user);

            return $next($request);
        }
    }
}
