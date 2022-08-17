<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\AssignmentResource;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OnlineClass $online_class, OnlineClassContent $content)
    {
        $assignments = Assignment::whereBelongsTo($content, 'content')->get();
        $data = collect($assignments)->map(fn ($assignment) => [
            'id' => $assignment->id,
            'title' => $assignment->title,
            'created_at' => $assignment->created_at->diffForHumans()
        ]);

        return $this->successResponse("Assignments from $content->title retrieved successfully", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OnlineClass $online_class, OnlineClassContent $content)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'deadline' => 'required|date'
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
        }

        // TODO: send notification to student who enroll on this online class
        $data = [
            'message' => "Kamu mendapat tugas baru pada $content->title dalam pelajaran $online_class->name",
            'details' => [
                'online_class_id' => $online_class->id,
                'content_id' => $content->id,
                'assignment_id' => $assignment->id,
                'assignment_title' => $assignment->title,
                'assignment_deadline' => Carbon::parse($assignment->deadline)->isoFormat('dddd, D MMM H:mm')
            ]
        ];
        Notification::send($students, new AssignmentNotification($data));

        return $this->acceptedResponse('Assignment created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        return $this->successResponse('Detail assignment retrieved successfully', new AssignmentResource($assignment));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'deadline' => 'required|date'
        ]);

        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        // TODO: sync assignment to students who enroll on this online class
        $students = $online_class->students()->get();
        if (!is_null($students)) {
            $assignment->students()->sync($students);
        }

        // TODO: send notification to student who enroll on this online class
        $data = [
            'message' => "Tugas kamu pada $content->title dalam pelajaran $online_class->name telah diperbarui",
            'details' => [
                'online_class_id' => $online_class->id,
                'content_id' => $content->id,
                'assignment_id' => $assignment->id,
                'assignment_title' => $assignment->title,
                'assignment_deadline' => Carbon::parse($assignment->deadline)->isoFormat('dddd, D MMM H:mm')
            ]
        ];
        Notification::send($students, new AssignmentUpdated($data));

        return $this->acceptedResponse('Assignment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineClass $online_class, OnlineClassContent $content, Assignment $assignment)
    {
        $assignment->delete();
        return $this->successResponse('Assignment deleted successfully');
    }
}
