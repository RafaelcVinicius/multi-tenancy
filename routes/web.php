<?php

use App\Models\Tenant;
use App\Models\Teste;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () use ($domain){
        Route::get("/", static fn () =>  view('welcome'));
        Route::prefix('users')->group(function () {
            Route::get("/", fn (Request $request) => User::get());
            Route::post("/", function (Request $request) {
        
                $user = User::factory()->create([
                    "name" => $request->get('name'),
                    "email" => $request->get('email'),
                ]);

                $tenant = Tenant::query()->create(attributes:[
                    'id' => $request->get('domain'),
                    'type' => $request->get('type')
                ]);

                $tenant->domains()->create(attributes:[
                    'domain'=> $request->get('domain').'.localhost'
                ]);

                if($request->get('type') == 'S'){

                    $tenant->run(function(Tenant $tenant) use ($request){
                        User::factory()->create([
                            "name" => $request->get('name'),
                            "email" => $request->get('email'),
                        ]);
                    });
                }else{
                    $tenant->run(function(Tenant $tenant) use ($request){
                        Teste::create();
                    });
                }
                    

                return $user;
            });
        });
    });
}
