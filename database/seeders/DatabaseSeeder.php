<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

   public function run(): void
{
    \App\Models\User::factory()->create([
        'name' => 'Admin Mahidio',
        'email' => 'admin@mahidio.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}
}
