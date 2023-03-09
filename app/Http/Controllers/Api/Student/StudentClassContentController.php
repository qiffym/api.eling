<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\ContentResource;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;

class StudentClassContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OnlineClass $my_class)
    {
        $contents = $my_class->contents;
        $data = collect($contents)->map(fn ($content) => [
            'id' => $content->id,
            'title' => $content->title,
            'description' => $content->desc,
            'created_at' => $content->created_at->diffForHumans(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All contents retrieved successfully',
            'online_class_name' => "$my_class->name (".$my_class->rombel_class->name.')',
            'data' => $data,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OnlineClass $my_class, OnlineClassContent $content)
    {
        return $this->successResponse('Detial online class retrieved successfully.', new ContentResource($content));
    }
}
