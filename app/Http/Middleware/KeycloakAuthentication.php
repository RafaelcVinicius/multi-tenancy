<?php

namespace App\Http\Middleware;

use App\Models\Payments;
use App\Models\PaymentsIntention;
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
                if (!str_contains($request->route()->uri, 'api/users')) {                 
                    return response()->json(['message' => 'Token de acesso ausente!'], 401);
                }
                else{
                    return $next($request);
                }
            } else {
                $publicKey = <<<EOD
                    -----BEGIN PUBLIC KEY-----
                    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxlsS17K7ynLCmAI1S1+rfAd3Y+NpLUhoDhQNyUYclAa8ySwm5jZlWJlEf3S1nYBmPBchrhsMpMleylYIizbuh5xiEmjk17rzNJjbJZ6Z7EOP7k39LY/RQCHZF/po5vHWMhGymI+NMfjZvHpLMNl41cFJ5VZ4PmckOKTLLuMljLoKMg5TTOBI9fHDMzEc7bpPvGb16uUovcNuP/V7RMSm4oE9F8Yz9cSov/2GpoFYTDhfp78Gjam0jTlE2WKj2GyABbLLUcmW3B15UsnZGSgZRLe04MDMVjrxdDfvjPy5ZRW/45trogEYDenzL+JbyxhoiiRSGCK0cVygAdLJqj7vnQIDAQAB
                    -----END PUBLIC KEY-----
                    EOD;

                $decodedToken = JWT::decode($token, new Key($publicKey, 'RS256'));

                $user = User::where('email', $decodedToken->email)->firstOrFail();
            }

            Auth::loginUsingId($user->id);

            return $next($request);
        } catch (\Exception) {
            return response()->json(['message' => 'Token de acesso inválido'], 401);
        }
    }
}