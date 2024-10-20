<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthentication
{

    protected $except = [
        '/api/register'
    ];


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // base64 authentication
        if (!in_array($request->getRequestUri(), $this->except)) {

           $authorizationData = explode(' ',$request->header('authorization'));

           $credential = explode(':',base64_decode($authorizationData[1]));

           $user = User::query()->where('email', $credential[0])->firstOrFail();

           if (! Hash::check($credential[1],$user->password)){
               abort(403);
           }

            auth()->login($user);

        }

        return $next($request);
    }
}
