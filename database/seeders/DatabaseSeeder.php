<?php

namespace Database\Seeders;
use Illuminate\Support\Str;;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'balance' => '50000.1234',
            'type' => 'admin',
            'paymentaddress' => Str::random(30),
            'password' => Hash::make('adminadmin'),
            // 'verified' => true
            
        ]);
    }
}
