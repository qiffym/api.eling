<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class DashboardController extends Controller
{
    public function users()
    {
        $total_users = User::count();
        $total_admin = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->count();
        $total_teachers = Teacher::count();
        $total_students = Student::count();

        $data = [
            'total_users' => $total_users,
            'total_admin' => $total_admin,
            'total_teachers' => $total_teachers,
            'total_students' => $total_students,
        ];

        return $this->okResponse('Data retrieved successfully', $data);
    }
}
