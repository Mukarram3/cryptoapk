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
            'name' => 'basic',
            'email' => '3',
            'password' => '12',
            'paymentaddress' => Str::random(30),
            'password' => Hash::make('adminadmin'),
            'verified' => true
            
        ]);
    }
}
