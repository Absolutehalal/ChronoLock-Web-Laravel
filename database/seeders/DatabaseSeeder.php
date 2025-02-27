<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('users')->insert([
            'firstName' => 'CCS Admin',
            'email' => 'admin@cspc.edu.ph',
            'idNumber' => 'Admin',
            'userType' => 'Admin',
            'password' => Hash::make('adminCCS'),
        ]);
    }
}
