<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        Role::create(['name' => 'Admin',
                    'guard_name' => 'web']);
        Role::create(['name' => 'Student',
                    'guard_name' => 'web']);
        Role::create(['name' => 'Professor',
                    'guard_name' => 'web']);
        Role::create(['name' => 'Proofreader',
                    'guard_name' => 'web']);
        Role::create(['name' => 'Executive Director',
                    'guard_name' => 'web']);
        Role::create(['name' => 'PBL Coordinator',
                    'guard_name' => 'web']);
        Role::create(['name' => 'English Cluster Head',
                    'guard_name' => 'web']);
        Role::create(['name' => 'Librarian',
                    'guard_name' => 'web']);

        
    }
}