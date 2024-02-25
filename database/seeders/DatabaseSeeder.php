<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\User::factory()->create([
             'name' => 'admin',
             'email' => 'admin@apc.edu.ph',
             'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
         ]);
         \App\Models\User::factory()->create([
            'name' => 'professor1',
            'email' => 'professor1@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'professor2',
            'email' => 'professor2@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'student1',
            'email' => 'student1@student.apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'student2',
            'email' => 'student2@student.apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => '1',
            'model_type' => 'App\Models\User',
            'model_id' => '1',
        ]);

    }
}
