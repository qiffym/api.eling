<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RombelClasses\RombelClassResource;
use App\Http\Resources\RombelClasses\DetailRombelClassResource;
use App\Models\RombelClass;
use Illuminate\Http\Request;

class RombelClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkRole();

        $rombel_classes = RombelClass::all();
        return $this->successResponse('Rombel classes retrieved successfully', RombelClassResource::collection($rombel_classes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->checkRole();

        try {
            $validatedData = $request->validate([
                'department_id' => 'required|exists:departments,id',
                'name' => 'required|unique:rombel_classes,name',
                'grade' => 'required|in:10,11,12'
            ]);

            $rombel_class = RombelClass::create($validatedData);
            return $this->acceptedResponse('New Rombel Class has been created successfully', $rombel_class);
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
            $rombel_class = RombelClass::find($id);
            return $this->successResponse('Detail rombel class retrieved successfully', new DetailRombelClassResource($rombel_class));
        } catch (\Throwable $th) {
            return $this->notFoundResponse('Not Found', ['message' => $th->getMessage()]);
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
        $this->checkRole();

        try {
            $request->validate([
                'department_id' => 'required|exists:departments,id',
                'name' => 'required|unique:rombel_classes,name,' . $id,
                'grade' => 'required|in:10,11,12'
            ]);

            $rombel_class = RombelClass::find($id);

            $rombel_class->department_id = $request->department_id;
            $rombel_class->name = $request->name;
            $rombel_class->grade = $request->grade;
            $rombel_class->save();
            return $this->acceptedResponse("Data $rombel_class->name has been updated successfully", $rombel_class);
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
        $this->checkRole();
        $getName = RombelClass::find($id)->name;

        RombelClass::find($id)->delete();
        return $this->successResponse("$getName has been deleted successfully");
    }

    public function checkRole()
    {
        /** @var \App\Models\User $auth * */
        $auth = auth()->user();
        if (!$auth->hasRole('admin')) {
            return $this->forbiddenResponse('Forbidden.');
        }
    }
}
