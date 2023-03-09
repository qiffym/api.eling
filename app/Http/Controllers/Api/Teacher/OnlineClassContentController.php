<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\ContentResource;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnlineClassContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OnlineClass $online_class)
    {
        $contents = OnlineClassContent::where('online_class_id', $online_class->id)->get();

        $data = collect($contents)->map(fn ($content) => [
            'id' => $content->id,
            'title' => $content->title,
            'description' => $content->desc,
            'created_at' => $content->created_at->diffForHumans(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All contents retrieved successfully',
            'online_class_name' => "$online_class->name (".$online_class->rombel_class->name.')',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OnlineClass $online_class)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'nullable',
            ]);

            $new = OnlineClassContent::create([
                'online_class_id' => $online_class->id,
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
    public function show(OnlineClass $online_class, OnlineClassContent $content)
    {
        return $this->okResponse('Detail content retrieved successfully', new ContentResource($content));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineClass $online_class, OnlineClassContent $content)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'nullable',
            ]);

            $content->update([
                'title' => $request->title,
                'desc' => $request->description,
            ]);

            return $this->acceptedResponse('Content updated successfully');
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
    public function destroy(OnlineClass $online_class, OnlineClassContent $content)
    {

        // delete all materials
        if ($content->materials) {
            $id_guru = $online_class->teacher->id;
            $content_title = str($content->title)->slug();
            $directory = "online-classes/$id_guru/$online_class->id/$content_title";
            Storage::deleteDirectory($directory);
        }

        $content->delete();

        return $this->okResponse('Content deleted successfully');
    }
}
