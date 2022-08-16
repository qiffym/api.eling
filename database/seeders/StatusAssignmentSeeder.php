<?php

namespace Database\Seeders;

use App\Models\StatusStudentAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusStudentAssignment::create([
            'name' => 'Tugas baru telah diberikan',
            'short_description' => 'Status awal ketika guru membuat assignment awal',
        ]);
        StatusStudentAssignment::create([
            'name' => 'Tugas sudah dikumpulkan',
            'short_description' => 'Status ketika siswa sudah mengumpulkan tugas',
        ]);
    }
}
