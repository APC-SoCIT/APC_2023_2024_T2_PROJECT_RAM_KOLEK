<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view-any ProjectSubmission',
                            'web_guard' => 'web']);
        Permission::create(['name' => 'view ProjectSubmission',
                            'web_guard' => 'web']);
        Permission::create(['name' => 'view ProjectSubmission',
                            'web_guard' => 'web']);
    }
}
