<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\MotivationalWordResource;
use App\Http\Resources\OnlineClasses\OnlineClassResource;
use App\Http\Resources\Users\Student\UpcomingAssignment;
use App\Models\MotivationalWord;
use App\Models\Student;

class DashboardController extends Controller
{
    public function MyOnlineClasses()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        // TODO: Ambil semua online class dari siswa
        $myClasses = $student->online_classes;

        return $this->successResponse('Online class '.$student->user->name.' retrieved successfully', OnlineClassResource::collection($myClasses));
    }

    public function UpcomingAssignments()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        // TODO: ambil assignment dari siswa yang belum dikerjakan
        $upcoming_assignments = $student->assignments()->where('status_id', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Upcoming assignment from '.$student->user->name.' retrieved successfully',
            'total' => $upcoming_assignments->count(),
            'data' => UpcomingAssignment::collection($upcoming_assignments),
        ]);
    }

    public function RandomMotivationalWord()
    {
        $word = MotivationalWord::inRandomOrder()->first();

        if (! $word) {
            return $this->okResponse('Data masih kosong.');
        }

        return $this->successResponse('Random motivational word retrieved successfully', new MotivationalWordResource($word));
    }
}
