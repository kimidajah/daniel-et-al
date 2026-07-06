<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $guru = User::factory()->create([
            'name' => 'Budi Guru',
            'email' => 'guru@example.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        \App\Models\TeacherProfile::create([
            'user_id' => $guru->id,
            'nip' => '198701022010121003',
            'subject' => 'Matematika',
        ]);

        User::factory()->create([
            'name' => 'Andi Piket',
            'email' => 'piket@example.com',
            'password' => bcrypt('password'),
            'role' => 'piket',
        ]);

        User::factory()->create([
            'name' => 'Siti TU',
            'email' => 'tu@example.com',
            'password' => bcrypt('password'),
            'role' => 'tu',
        ]);

        User::factory()->create([
            'name' => 'Rahmat Kepsek',
            'email' => 'kepala@example.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_sekolah',
        ]);
    }
}
