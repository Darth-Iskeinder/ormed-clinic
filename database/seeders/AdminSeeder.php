<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.io',
            'email_verified_at' => now(),
            'password' => '40bd001563085fc35165329ea1ff5c5ecbdbbeef',
        ]);

        $user->assignRole('writer', 'admin');
    }
}
