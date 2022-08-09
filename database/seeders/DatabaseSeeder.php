<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            RombelClassSeeder::class,
            UserSeeder::class,
            OnlineClassSeeder::class,
            OnlineClassContentSeeder::class,
        ]);
    }
}
