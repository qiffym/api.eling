<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\DetailOnlineClassResource;
use App\Http\Resources\OnlineClasses\OnlineClassResource;
use Illuminate\Support\Facades\Auth;

class StudentClassController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $myClasses = $student->online_classes;

        return $this->successResponse('Online class '.$student->user->name.' retrieved successfully', OnlineClassResource::collection($myClasses));
    }

    public function show($id)
    {
        $student = Auth::user()->student;
        $myClass = $student->online_classes()->whereId($id)->firstOrFail();

        return $this->successResponse('Detail class retrieved successfully', new DetailOnlineClassResource($myClass));
    }
}
