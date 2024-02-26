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
            'name' => 'Michael Scott',
            'email' => 'mscott@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Jan Levinson',
            'email' => 'jlevinson@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Leila Arcega',
            'email' => 'lbarcega@student.apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Jeb Vincent Cajayon',
            'email' => 'jgcajayon@student.apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Lyka Tesorero',
            'email' => 'lctesorero@student.apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Regina George',
            'email' => 'rgeorge@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Cady Heron',
            'email' => 'cheron@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Frodo Baggins',
            'email' => 'fbaggins@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Samwise Gamgee',
            'email' => 'sgamgee@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Peregrin Took',
            'email' => 'ptook@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Padme Amidala',
            'email' => 'pamidala@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Char Aznable',
            'email' => 'caznable@apc.edu.ph',
            'password' => '$2y$12$hfx9Baxm0h.75dUv4oqeJulx1RMUJHEBXlpASBOoZG4lj/P2nTp2u',
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => '1',
            'model_type' => 'App\Models\User',
            'model_id' => '1',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '3',
            'model_type' => 'App\Models\User',
            'model_id' => '2',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '3',
            'model_type' => 'App\Models\User',
            'model_id' => '3',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '2',
            'model_type' => 'App\Models\User',
            'model_id' => '4',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '2',
            'model_type' => 'App\Models\User',
            'model_id' => '5',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '2',
            'model_type' => 'App\Models\User',
            'model_id' => '6',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '5',
            'model_type' => 'App\Models\User',
            'model_id' => '7',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '5',
            'model_type' => 'App\Models\User',
            'model_id' => '8',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '7',
            'model_type' => 'App\Models\User',
            'model_id' => '9',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '4',
            'model_type' => 'App\Models\User',
            'model_id' => '10',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '4',
            'model_type' => 'App\Models\User',
            'model_id' => '11',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '8',
            'model_type' => 'App\Models\User',
            'model_id' => '12',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '6',
            'model_type' => 'App\Models\User',
            'model_id' => '13',
        ]);
    }
}
