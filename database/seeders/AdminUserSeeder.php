<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin= User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
            'name' => 'Admin',
            'surname'=>'admin',
            'username'=>'admin',
            'email' => 'admin@gmail.com',
            'telephone'=> '600112233',
            'password' => Hash::make('admin1234'), 
            'user_type' => 'admin',

        ]);
         
            $admin->tokens()->delete();
          
           $token = $admin->createToken('AdminToken')->accessToken;

           file_put_contents(storage_path('admin_token.txt'), $token);
    }
}
