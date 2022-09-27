<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\DetailOnlineClassResource;
use App\Models\OnlineClass;
use App\Models\RombelClass;
use App\Models\User;
use Illuminate\Http\Request;

class OnlineClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher_id = User::find(auth()->user()->id)->teacher->id;
        $online_classes = OnlineClass::where('teacher_id', $teacher_id)->latest()->get();

        $data = collect($online_classes)->map(fn ($oc) => [
            'id' => $oc->id,
            'name' => $oc->name,
            'description' => $oc->desc,
            'class' => $oc->rombel_class->name,
            'teacher_id' => $oc->teacher_id,
            'teacher_avatar' => $oc->teacher->user->avatar ? asset('storage/' . $oc->teacher->user->avatar) : $oc->teacher->user->gravatar,
            'teacher_name' => $oc->teacher->user->name,
            'created_at' => $oc->created_at->isoFormat('dddd, D MMMM Y'),
            'updated_at' => $oc->updated_at->diffForHumans(),
        ]);

        return $this->okResponse('Your online class retrieved successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'nullable',
                'rombel_class_id' => 'required|exists:rombel_classes,id',
            ]);

            // add new online class
            $oc = OnlineClass::create([
                'teacher_id' => $request->user()->teacher->id,
                'name' => $request->name,
                'desc' => $request->description,
                'rombel_class_id' => $request->rombel_class_id,
            ]);

            $rombel = RombelClass::find($request->rombel_class_id);

            // enroll student from rombel_class
            if (!is_null($rombel->students)) {
                $oc->students()->sync($rombel->students()->get());
            }

            return $this->acceptedResponse("New online class created successfully for class $rombel->name students");
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $oc = OnlineClass::where('teacher_id', auth()->user()->teacher->id)->where('id', $id)->first();
            $message = "Detail online class named $oc->name for " . $oc->rombel_class->name . ' retrieved successfully';

            return $this->okResponse($message, new DetailOnlineClassResource($oc));
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
            // return $this->forbiddenResponse('You cannot see online class that created by other teachers');
        }
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
                'name' => 'required|string',
                'description' => 'nullable',
                'rombel_class_id' => 'required|exists:rombel_classes,id',
            ]);

            // add new online class
            $new = OnlineClass::updateOrCreate(['id' => $id, 'teacher_id' => $request->user()->teacher->id], [
                'name' => $request->name,
                'desc' => $request->description,
                'rombel_class_id' => $request->rombel_class_id,
            ]);

            $rombel = RombelClass::find($request->rombel_class_id);

            // enroll student from rombel_class
            if (!is_null($rombel->students)) {
                $oc = OnlineClass::find($new->id);
                $oc->students()->sync($rombel->students->pluck('id'));
            }

            return $this->acceptedResponse('online class updated successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
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
        try {
            $oc = OnlineClass::where('teacher_id', auth()->user()->teacher->id)->where('id', $id)->first();
            $oc_name = $oc->name;
            $oc->delete();

            return $this->okResponse("Online class named $oc_name deleted successfully");
        } catch (\Throwable $th) {
            return $this->forbiddenResponse('You cannot delete online class that created by other teachers');
        }
    }
}
