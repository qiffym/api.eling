<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role::create([
        //     'name' => 'super admin',
        //     'guard_name' => 'web',
        // ]);
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        Role::create([
            'name' => 'teacher',
            'guard_name' => 'web',
        ]);
        // Role::create([
        //     'name' => 'family',
        //     'guard_name' => 'web',
        // ]);
        Role::create([
            'name' => 'student',
            'guard_name' => 'web',
        ]);
    }
}
