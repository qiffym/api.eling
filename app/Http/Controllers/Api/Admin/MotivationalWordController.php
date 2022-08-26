<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MotivationalWordResource;
use App\Models\MotivationalWord;
use Illuminate\Http\Request;

class MotivationalWordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkRole();
        $motivational_words = MotivationalWord::latest()->paginate(20);

        return $this->successResponse('Motivational words retrieved successfully', MotivationalWordResource::collection($motivational_words));
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
                'title' => 'nullable|string',
                'body' => 'required',
                'from' => 'nullable|string',
                'active' => 'required|boolean',
            ]);

            $new = MotivationalWord::create($validatedData);

            return $this->acceptedResponse('New motivational word created successfully', new MotivationalWordResource($new));
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
            $motivational_word = MotivationalWord::find($id);

            return $this->successResponse('Motivational word retrieved successfully', new MotivationalWordResource($motivational_word));
        } catch (\Throwable $th) {
            return $this->notFoundResponse('Not Found.', ['message' => $th->getMessage()]);
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
            $validatedData = $request->validate([
                'title' => 'nullable|string',
                'body' => 'required',
                'from' => 'nullable|string',
                'active' => 'required|boolean',
            ]);

            MotivationalWord::where('id', $id)->update($validatedData);

            return $this->acceptedResponse('Motivational word updated successfully', new MotivationalWordResource(MotivationalWord::find($id)));
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
        MotivationalWord::find($id)->delete();

        return $this->successResponse('Motivational word deleted successfully');
    }

    public function checkRole()
    {
        /** @var \App\Models\User $auth * */
        $auth = auth('api')->user();
        if (!$auth->hasRole('admin')) {
            return $this->forbiddenResponse('Forbidden.');
        }
    }
}
