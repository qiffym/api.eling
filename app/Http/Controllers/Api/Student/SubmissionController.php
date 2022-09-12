<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Submissions\SubmissionRecource;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use App\Models\Submission;
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
        $submission = $student->assignments()->where('assignment_id', $id)->first();
        return $this->successResponse("Submission detail retrieved successfully", new SubmissionRecource($submission));
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
                'file' => 'required|file',
            ]);

            $student = Auth::user()->student;
            // $submission = $student->assignments()->where('assignment_id', $id)->first();
            $submission = $student->assignments();
            $content = $submission->where('assignment_id', $id)->first()->content->title;

            // $nama_guru = str($online_class->teacher->user->name)->camel();
            // $oc_name = str($online_class->name)->camel();
            $student_name = str($student->user->name)->camel();
            $content_name = str($content)->camel();
            $string_path = "uploads/submissions/$student_name/$content_name";

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->storeAs($string_path, str($file->getClientOriginalName())->camel());
            }

            $submission->updateExistingPivot($id, ['file' => $path, 'status_id' => 2, 'submitted_at' => now()]);

            return $this->okResponse('Upload success.');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
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
