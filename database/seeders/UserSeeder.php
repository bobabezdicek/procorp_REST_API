<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $name = [
            'Robertson',
            'Arroyo',
            'Lang',
            'McCarty',
            'Heath'
        ];

        $email = [
            'Robertson@gmail.com',
            'Arroyo@gmail.com',
            'Lang@gmail.com',
            'McCarty@gmail.com',
            'Heath@gmail.com'
        ];

        $password = [
            'Robertson123',
            'Arroyo123',
            'Lang123',
            'McCarty123',
            'Heath123'
        ];

        $role = [
            'user',
            'user',
            'admin',
            'user',
            'user'
        ];

        for ($i = 0; $i < 5; $i++) {
            DB::table('users')->insert([
                'name' => $name[$i],
                'email' => $email[$i],
                'password' => Hash::make($password[$i]),
                'role' => $role[$i],
                'api_token' => Str::random(50)
            ]);
        }

    }
}
