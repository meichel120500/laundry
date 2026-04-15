<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'id_level'=> '1',
        ]);

        User::create([
            'name'=> 'Operator',
            'email'=> 'operator@example.com',
            'password'=> Hash::make('password123'),
            'id_level'=> '2',
        ]);

        User::create([
            'name'=> 'Pimpinan',
            'email'=> 'pimpinan@example.com',
            'password'=> Hash::make('password123'),
            'id_level'=> '3',
        ]);

    }
}
