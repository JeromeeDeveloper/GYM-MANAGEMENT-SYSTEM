<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Jerome User',
                'email' => 'porcadojerome@gmail.com',
                'phone' => '09183033069',
                'capabilities' => '0,1,2,3,4,5,6,7',
                'email_verified_at' => now(),
                'password' => Hash::make('jerome123'), // Hashed password
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample users if needed
        ]);
    }
}
