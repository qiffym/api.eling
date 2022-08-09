<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\ContentResource;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use Illuminate\Http\Request;

class OnlineClassContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($online_class)
    {
        try {
            $oc = OnlineClass::find($online_class);
            $contents = OnlineClassContent::where('online_class_id', $online_class)->get();
            $data = collect($contents)->map(fn ($content) => [
                'id' => $content->id,
                'title' => $content->title,
                'description' => $content->desc,
                'created_at' => $content->created_at->diffForHumans(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All contents retrieved successfully',
                'online_class_name' => "$oc->name (".$oc->rombel_class->name.')',
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return $this->notFoundResponse('Not Found.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $online_class)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'nullable',
            ]);

            $new = OnlineClassContent::create([
                'online_class_id' => $online_class,
                'title' => $request->title,
                'desc' => $request->description,
            ]);

            return $this->acceptedResponse('New content created successfully', $new);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($online_class, $id)
    {
        try {
            $content = OnlineClassContent::where('online_class_id', $online_class)->where('id', $id)->first();

            return $this->okResponse('Detail content retrieved successfully', new ContentResource($content));
        } catch (\Throwable $th) {
            return $this->notFoundResponse('Not Found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $online_class, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'nullable',
            ]);

            $update = OnlineClassContent::updateOrCreate(['online_class_id' => $online_class, 'id' => $id], [
                'title' => $request->title,
                'desc' => $request->description,
            ]);

            return $this->acceptedResponse('Content updated successfully', $update);
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
    public function destroy($online_class, $id)
    {
        $content = OnlineClassContent::where('online_class_id', $online_class)->where('id', $id)->first();
        // delete material

        $content->delete();

        return $this->okResponse('Content deleted successfully');
    }
}
