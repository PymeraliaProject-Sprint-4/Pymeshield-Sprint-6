<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $client = User::create([
            'name' => 'Client',
            'last_name' => 'Pymeshield',
            'email' => 'client@pymeshield.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'nick_name' => 'client',
            'phone' => fake()->phoneNumber(),
            'type' => 'client',
            'remember_token' => Str::random(10),
            'company_id' => 1,
        ]);

        $client->assignRole('client');

        $worker = User::create([
            'name' => 'Worker',
            'last_name' => 'Pymeshield',
            'email' => 'worker@pymeshield.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'nick_name' => 'worker',
            'phone' => fake()->phoneNumber(),
            'type' => 'worker',
            'remember_token' => Str::random(10),
            'company_id' => 1,
        ]);

        $worker->assignRole('worker');

        $admin = User::create([
            'name' => 'Admin',
            'last_name' => 'Pymeshield',
            'email' => 'admin@pymeshield.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'nick_name' => 'admin',
            'phone' => fake()->phoneNumber(),
            'type' => 'admin',
            'remember_token' => Str::random(10),
            'company_id' => 1,
        ]);

        $admin->assignRole('admin');

    }
}
