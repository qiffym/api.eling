<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\AssignmentResource;
use App\Http\Resources\OnlineClasses\DetailAssignmentResource;
use App\Http\Resources\OnlineClasses\GradedResource;
use App\Http\Resources\OnlineClasses\UnGradingResource;
use App\Http\Resources\OnlineClasses\UnSubmittedResource;
use App\Models\Assignment;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use App\Notifications\AssignmentNotification;
use App\Notifications\AssignmentUpdated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AssignmentController extends Controller
{
    public function index(OnlineClass $online_class, OnlineClassContent $content)
    {
        $assignments = Assignment::whereBelongsTo($content, 'content')->get();

        return $this->successResponse("Assignments from $content->title retrieved successfully", AssignmentResource::collection($assignments));
    }

    public function store(Request $request, OnlineClass $online_class, OnlineClassContent $content)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        $assignment = Assignment::create([
            'online_class_content_id' => $content->id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);
        $students = $online_class->students()->get();

        // TODO: attach assignment to students who enroll on this online class
        if (!is_null($students)) {
            $assignment->students()->attach($students, ['status_id' => 1]);

            // TODO: send notification to student who enroll on this online class
            $data = [
                'message' => "Kamu mendapat tugas baru pada $content->title dalam pelajaran $online_class->name",
                'details' => [
                    'online_class_id' => $online_class->id,
                    'content_id' => $content->id,
                    'assignment_id' => $assignment->id,
                    'assignment_title' => $assignment->title,
                    'assignment_deadline' => Carbon::parse($assignment->deadline)->isoFormat('dddd, D MMM H:mm'),
                ],
            ];
            Notification::send($students, new AssignmentNotification($data));
        }

        return $this->acceptedResponse('Assignment created successfully');
    }

    public function show(OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        return $this->successResponse('Detail assignment retrieved successfully', new DetailAssignmentResource($assignment));
    }

    public function update(Request $request, OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        // TODO: sync assignment to students who enroll on this online class
        $students = $online_class->students()->get();
        if (!is_null($students)) {
            $assignment->students()->attach($students, ['status_id' => 1]);
            // $assignment->students()->syncWithPivotValues($students, ['status_id' => 1]);
        }

        // TODO: send notification to student who enroll on this online class
        $data = [
            'message' => "Tugas kamu pada $content->title dalam pelajaran $online_class->name telah diperbarui",
            'details' => [
                'online_class_id' => $online_class->id,
                'content_id' => $content->id,
                'assignment_id' => $assignment->id,
                'assignment_title' => $assignment->title,
                'assignment_deadline' => Carbon::parse($assignment->deadline)->isoFormat('dddd, D MMM H:mm'),
            ],
        ];
        Notification::send($students, new AssignmentUpdated($data));

        return $this->acceptedResponse('Assignment updated successfully');
    }

    public function destroy(OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $assignment->delete();

        return $this->successResponse('Assignment deleted successfully');
    }

    public function unSubmitted(OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $query = $assignment->students()->wherePivot('submitted_at', null)->get();

        return $this->okResponse('Ungrading retireved', UnSubmittedResource::collection($query));
    }

    public function unGrading(OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $query = $assignment->students()->wherePivot('submitted_at', '!=', null)->wherePivotNull('score')->get();

        return $this->okResponse('Ungrading retireved', UnGradingResource::collection($query));
    }

    public function graded(OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $query = $assignment->students()->wherePivotNotNull('submitted_at')->wherePivotNotNull('score')->get();

        return $this->okResponse('Ungrading retireved', GradedResource::collection($query));
    }

    public function grade(Request $request, OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'score' => 'required|max:100',
        ]);

        //# Grade
        $assignment->students()->updateExistingPivot($request->student_id, ['score' => $request->score]);

        return $this->acceptedResponse('Tugas berhasil dinilai.');
    }
}
