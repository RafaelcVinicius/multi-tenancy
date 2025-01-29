<?php

namespace App\Repositories\Keycloak;

use App\Repositories\Keycloak\Contracts\KeycloakRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class KeycloakRepository implements KeycloakRepositoryInterface
{
    private function auth() {
        $request = Http::asForm()->post('https://auth-hom.fortepagamentos.com.br/realms/multi-tenancy/protocol/openid-connect/token', [
            'client_id' => 'api',
            'client_secret' => 'fR0Nq3vxfRDtzQX6fcTrZizr49JSNLw4',
            'grant_type' => 'client_credentials',
        ]);
        
        if (!$request->ok()) {
            return response()->json(['statusCode' => Response::HTTP_BAD_REQUEST, 'message' => 'Erro'], Response::HTTP_BAD_REQUEST);
        }
        return $request->json()['access_token'];
    }

    public function token(array $data){
            $credential = null;

            if($data['grantType'] == 'client_credentials'){
                $credential = array(
                    'client_id' => $data['clientId'],
                    'client_secret' => $data['clientSecret'],
                    'grant_type' => $data['grantType'],
                );
            }
            else{
                $credential = array(
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'client_id' => 'api',
                    'client_secret' => "fR0Nq3vxfRDtzQX6fcTrZizr49JSNLw4",
                    'grant_type' => $data['grantType'],
                );
            }


        $request = Http::asForm()->post('https://auth-hom.fortepagamentos.com.br/realms/multi-tenancy/protocol/openid-connect/token', $credential);
        
        if (!$request->ok()) {
            return response()->json(['statusCode' => Response::HTTP_BAD_REQUEST, 'message' => 'Erro'], Response::HTTP_BAD_REQUEST);
        }
    
        return $request->json();
    }

    public function storeUser(array $data) {
        $teste = $this->auth();
        $request = Http::withToken($teste)->post('https://auth-hom.fortepagamentos.com.br/admin/realms/multi-tenancy/users', [
                "email" =>  $data["email"],
                "emailVerified" =>  $data["emailVerified"],
                "enabled" =>  $data["enabled"],
                "firstName" =>  $data["firstName"],
                "lastName" =>  $data["lastName"],
                "username" =>  $data["userName"],
                "credentials" => [$data['credentials']]
            ]);
        
        if (!$request->created()) {
            return response()->json(['statusCode' => Response::HTTP_BAD_REQUEST, 'message' => 'Erro'], Response::HTTP_BAD_REQUEST);
        }

        $requestGet = Http::withToken($teste)->get('https://auth-hom.fortepagamentos.com.br/admin/realms/multi-tenancy/users?username='. $data["userName"]);

        if (!$requestGet->ok()) {
            return response()->json(['statusCode' => Response::HTTP_BAD_REQUEST, 'message' => 'Erro'], Response::HTTP_BAD_REQUEST);
        }

        return  $requestGet->json() ? $requestGet->json()[0] : null;
    }

    public function findUser(string $data) {
        $teste = $this->auth();
        $request = Http::withToken($teste)->get('https://auth-hom.fortepagamentos.com.br/admin/realms/multi-tenancy/users?username='. $data);
        
        if (!$request->ok()) {
            return response()->json(['statusCode' => Response::HTTP_BAD_REQUEST, 'message' => 'Erro'], Response::HTTP_BAD_REQUEST);
        }

        return $request->json();
    }

    public function showUser(array $data){

    }
}
