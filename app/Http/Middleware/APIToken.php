<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class APIToken
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
        if ($request->header('Authorization')) {
            $token = $request->bearerToken();
            $user = User::where('token', $token)->first();
            if($user && ($user->token_expires_on>date("Y-m-d H:i:s"))){
                return $next($request);
            }else{
                return response()->json([
                    'message' => 'Invalid Token',
                ], 401);
            }           
        }
        return response()->json([
            'message' => 'Unauthorized',
        ], 401);
    }
}
