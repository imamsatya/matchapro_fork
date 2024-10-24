<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AutoLoginSSO
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
        if(Auth::check()){
            return $next($request);
        }
        
        $provider = new \JKD\SSO\Client\Provider\Keycloak([
            'authServerUrl'         => env('SSO_AUTH_SERVER_URL'),
            'realm'                 => env('SSO_REALM'),
            'clientId'              => env('SSO_CLIENT_ID'),
            'clientSecret'          => env('SSO_CLIENT_SECRET'),
            // 'redirectUri'           => route('login')
        ]); 

        
        try {
            $token = session('sso_token');                      
            if($token && !$token->hasExpired()) {
                $userSSO = $provider->getResourceOwner($token);
                $userDB = User::where('username', $userSSO->getUsername())->first();

                if($userDB) {
                    Auth::login($userDB);
                    return $next($request); 
                }
            }
        } catch (Exception $e) {
            return redirect()->route('login');
        }

        return redirect()->route('login');

    }
}