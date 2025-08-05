<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'Amit Ben-Raphael',
                'email' => 'amit@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Angilyn Felix',
                'email' => 'angilyn@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'James Lui',
                'email' => 'james@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arslan Aslam',
                'email' => 'beingfastian@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];


        // Optionally, you can add more users
        $faker = Factory::create();
        for ($i=0; $i < rand(10, 50); $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail  ,
                'password' => Hash::make('password'),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => now(),
            ];
        }

        User::insert($users);
    }
}
