<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'developer',
            'email' => 'developer@example.com',
            'password' => Hash::make('Test@Password123#'),
            'created_at' => now(), 'updated_at' => now()             
        ]);
    }
}
