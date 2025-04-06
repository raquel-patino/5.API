<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('oauth_clients')->truncate(); // limpio si ya había clientes

        DB::table('oauth_clients')->insert([
            [
                'id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
                'user_id' => null,
                'name' => 'Personal Access Client',
                'secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
                'redirect' => 'http://localhost/',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                
            'id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'user_id' => null,
            'name' => 'Password Grant Client',
            'secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'redirect' => 'http://localhost',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
    }
}
