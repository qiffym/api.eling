<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\MaterialResource;
use App\Models\Material;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OnlineClass $online_class, OnlineClassContent $content)
    {
        try {
            $materials = Material::where('online_class_content_id', $content->id)->get();
            $data = collect($materials)->map(fn ($material) => [
                'id' => $material->id,
                'title' => $material->title,
                'file' => Storage::url($material->file),
                'created_at' => $material->created_at->diffForHumans(),
            ]);

            return $this->successResponse("All materials from $content->title of $online_class->name (" . $online_class->rombel_class->name . ") retrieved successfully", $data);
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
    public function store(Request $request, OnlineClass $online_class, OnlineClassContent $content)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'file' => 'required|file',
            ]);

            $id_guru = $online_class->teacher->id;
            $content_title = str($content->title)->slug();
            $string_path = "online-classes/$id_guru/$online_class->id/$content_title/materials";

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->storeAs($string_path, $file->getClientOriginalName());
            }

            Material::create([
                'online_class_content_id' => $content->id,
                'title' => $request->title,
                'file' => $path
            ]);

            return $this->acceptedResponse('New material created successfully');
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
    public function show(OnlineClass $online_class, OnlineClassContent $content, Material $material)
    {
        try {
            return $this->okResponse('Materi retrieved sucessfully', new MaterialResource($material));
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
    public function update(Request $request, OnlineClass $online_class, OnlineClassContent $content, Material $material)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'file' => 'nullable|file',
            ]);

            $id_guru = $online_class->teacher->id;
            $content_title = str($content->title)->slug();
            $string_path = "online-classes/$id_guru/$online_class->id/$content_title/materials";

            if ($request->hasFile('file')) {
                Storage::delete($material->file);

                $file = $request->file('file');
                $path = $file->storeAs($string_path, $file->getClientOriginalName());
                $material->file = $path;
            }

            $material->title = $request->title;
            $material->save();

            return $this->acceptedResponse('material updated successfully');
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
    public function destroy(OnlineClass $online_class, OnlineClassContent $content, Material $material)
    {
        if ($material->file) {
            Storage::delete($material->file);
        }

        $material->delete();
        return $this->successResponse('Material deleted successfully');
    }
}
