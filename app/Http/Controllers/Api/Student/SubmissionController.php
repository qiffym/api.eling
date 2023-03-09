<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Submissions\SubmissionRecource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Auth::user()->student;
        $assignmentDetail = $student->assignments()->where('assignment_id', $id)->first();

        if (!$assignmentDetail) {
            return $this->notFoundResponse('Tugas ini tidak tersedia untukmu, silahkan lapor ke guru pengajar');
        }

        return $this->successResponse('Submission detail retrieved successfully', new SubmissionRecource($assignmentDetail));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:51200',
            ]);

            $student = Auth::user()->student;
            $studentAssignments = $student->assignments();
            $submission = $student->assignments()->where('assignment_id', $id)->first();
            $content = $submission->content->title;

            $student_name = str($student->user->name)->camel();
            $rombel_name = str($student->rombel_class->name)->camel();
            $oc_name = str($submission->content->online_class->name)->camel();
            $content_name = str($content)->camel();
            $string_path = "uploads/submissions/$rombel_name/$student_name/$oc_name/$content_name";

            // Store File
            $file = $request->file('file');
            $path = $file->storeAs($string_path, str($file->getClientOriginalName())->camel());

            $deadline = $submission->deadline;
            if ($deadline > now() == false) {
                $studentAssignments->updateExistingPivot($id, ['file' => $path, 'status_id' => 3, 'submitted_at' => now()]);
                return $this->okResponse('Mengumpulkan terlambat.');
            }

            $studentAssignments->updateExistingPivot($id, ['file' => $path, 'status_id' => 2, 'submitted_at' => now()]);
            return $this->okResponse('Tugas berhasil dikumpulkan.');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
