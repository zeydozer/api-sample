<?php

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use Closure;
use Illuminate\Http\Request;

use App\Models\User;

class AuthMock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = explode(' ', $request->header('Authorization'));
        if ($token[0] == 'Basic') {
            list($u_id, $app_id) = explode(':', base64_decode($token[1]));
            $user = User::where('u_id', $u_id)->where('app_id', $app_id)->first();
            if ($user && $user->token) {
                $request->merge(['user' => new UserResource($user)]);
                return $next($request);
            }
        }
        return response()->json(['Auth başarısız (mock).'], 401);
    }
}