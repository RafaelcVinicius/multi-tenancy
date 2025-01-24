<?php

use App\Models\Tenant;
use App\Models\Teste;
use App\Models\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () use ($domain){
        Route::get("/", static fn () =>  view('welcome'));
        Route::prefix('users')->group(function () {
            Route::get("/", fn (Request $request) => User::get());
            Route::post("/", function (Request $request) {
        
                Log::info('Teste do testedwdwad');

                $user = User::factory()->create([
                    "name" => $request->get('name'),
                    "email" => $request->get('email'),
                ]);

                Log::info('Teste do testeddwadaw');

                if($request->get('type') == 'S'){

                    // $tenant->run(function(Tenant $tenant) use ($request){
                    //     User::factory()->create([
                    //         "name" => $request->get('name'),
                    //         "email" => $request->get('email'),
                    //     ]);
                    // });
                }else{
        
                    Log::info('Teste do teste');
                    Log::info($request->get('name'));
                }

                return $user;
            });
        });
    });
}
