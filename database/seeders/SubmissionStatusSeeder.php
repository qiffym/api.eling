<?php

namespace Database\Seeders;

use App\Models\SubmissionStatus;
use Illuminate\Database\Seeder;

class SubmissionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubmissionStatus::create([
            'name' => 'Tugas baru telah diberikan',
            'short_description' => 'Status awal ketika guru membuat assignment awal',
        ]);
        SubmissionStatus::create([
            'name' => 'Tugas sudah dikumpulkan',
            'short_description' => 'Status ketika siswa sudah mengumpulkan tugas',
        ]);
    }
}
