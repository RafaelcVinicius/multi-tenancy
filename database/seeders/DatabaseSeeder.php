<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::query()->create(attributes:[
            'id' => 'rafael'
        ]);
        

        $tenant->domains()->create(attributes:[
            'domain'=> 'rafael.localhost'
        ]);

        Tenant::all()->runForEach(function(Tenant $tenant){
            User::factory()->create([
                "name" => "vini",
                "email" => "vini@gmail.com",
            ]);
        });
    }
}
