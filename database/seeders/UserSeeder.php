<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin
        $admin = User::create([
            'name' => 'Qiff Ya Muhammad',
            'username' => 'qiffym',
            'email' => 'qiww.dev@gmail.com',
            'email_verified_at' => now(),
            'password' => '12345',
            'remember_token' => Str::random(10),
        ]);
        $admin->assignRole('admin');

        // guru
        $teacher = User::create([
            'name' => 'Guru A',
            'username' => 'gurua',
            'email' => 'gurua@email.com',
            'email_verified_at' => now(),
            'password' => '12345',
            'remember_token' => Str::random(10),
        ]);
        $teacher->assignRole('teacher');
        Teacher::create(['user_id' => $teacher->id]);

        // // ortu
        // $family = User::create([
        //     'name' => 'Orang tua A',
        //     'username' => 'ortua',
        //     'email' => 'ortua@email.com',
        //     'email_verified_at' => now(),
        //     'password' => '12345',
        //     'remember_token' => Str::random(10),
        // ]);
        // $family->assignRole('family');
        // Family::create(['user_id' => $family->id]);

        // siswa
        $student = User::create([
            'name' => 'Siswa A',
            'username' => 'siswaa',
            'email' => 'siswaa@email.com',
            'email_verified_at' => now(),
            'password' => '12345',
            'remember_token' => Str::random(10),
        ]);
        $student->assignRole('student');
        Student::create(['user_id' => $student->id, 'rombel_class_id' => 1]);

        // siswa 2
        $student2 = User::create([
            'name' => 'Siswa B',
            'username' => 'siswab',
            'email' => 'siswab@email.com',
            'email_verified_at' => now(),
            'password' => '12345',
            'remember_token' => Str::random(10),
        ]);
        $student2->assignRole('student');
        Student::create(['user_id' => $student2->id, 'rombel_class_id' => 1]);

        // siswa 3
        $student3 = User::create([
            'name' => 'Siswa C',
            'username' => 'siswac',
            'email' => 'siswac@email.com',
            'email_verified_at' => now(),
            'password' => '12345',
            'remember_token' => Str::random(10),
        ]);
        $student3->assignRole('student');
        Student::create(['user_id' => $student3->id, 'rombel_class_id' => 1]);

        // siswa 4
        $student4 = User::create([
            'name' => 'Siswa D',
            'username' => 'siswad',
            'email' => 'siswad@email.com',
            'email_verified_at' => now(),
            'password' => '12345',
            'remember_token' => Str::random(10),
        ]);
        $student4->assignRole('student');
        Student::create(['user_id' => $student4->id, 'rombel_class_id' => 2]);
    }
}
