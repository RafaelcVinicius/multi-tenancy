<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;

class KeycloakAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        try {
            if (!$token) {
                if (str_contains(request()->url(), 'api/v1/users') || str_contains(request()->url(), 'api/v1/auth/token')){                 
                    return $next($request);
                }
                else{
                    return response()->json(['message' => 'Token de acesso ausente!'], 401);
                }
            } else {
                $publicKey = <<<EOD
                    -----BEGIN PUBLIC KEY-----
                    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnZG/QPzhdQ1c412scrIYCg3C3QuA+4hV5CLzvrcGeYXeSj/K/PJvc2MFzcfcsEqURzP0StMDFI8a3uvfWimmF5LueuyWcbG+C6mgIAgx5XdcJE12+nN6aF1aDh8zk0GoHjyu/DZC0kbAydSMkm0Sqz5Q9ob3v8wxfdhYkDWRSMPKP2yUZ9i10Lx1IzYk12MgUL6fENLMdjWL0zfZQJnmQSySXgBaVF5hEuShvCSWbKLlzlsuZiA1737x+Re7950c7KjaqWzcki+/lP10uZBFVsxyHuUGNnF3uZfYgOAd6qgIgXcjmyTwRqb1I2DLI1bdBBzV/o9Dco3H/GCkLyifGwIDAQAB
                    -----END PUBLIC KEY-----
                    EOD;

                $decodedToken = JWT::decode($token, new Key($publicKey, 'RS256'));

                $user = User::where('keycloak_id', $decodedToken->sub)->firstOrFail();
            }

            Auth::loginUsingId($user->id);
            
            return $next($request);
        } catch (\Exception) {
            return response()->json(['message' => 'Token de acesso inválido'], 401);
        }
    }
}