<?php

namespace Database\Seeders;

use App\Models\RombelClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RombelClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RombelClass::create([
            'department_id' => 1,
            'name' => 'X TKJ 1',
            'grade' => '10'
        ]);
        RombelClass::create([
            'department_id' => 1,
            'name' => 'X TKJ 2',
            'grade' => '10'
        ]);
        RombelClass::create([
            'department_id' => 1,
            'name' => 'XI TKJ 1',
            'grade' => '11'
        ]);
        RombelClass::create([
            'department_id' => 1,
            'name' => 'XI TKJ 2',
            'grade' => '11'
        ]);
        RombelClass::create([
            'department_id' => 1,
            'name' => 'XII TKJ 1',
            'grade' => '12'
        ]);
        RombelClass::create([
            'department_id' => 1,
            'name' => 'XII TKJ 2',
            'grade' => '12'
        ]);
    }
}
