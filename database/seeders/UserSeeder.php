<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Auto Generated User 1',
            'email' => 'test1@example.com',
            'password' => '123456'
        ]);
        event(new Registered($user));

        $user = User::factory()->create([
            'name' => 'Auto Generated User 2',
            'email' => 'test2@example.com',
            'password' => '123456'
        ]);
        event(new Registered($user));
    }
}
