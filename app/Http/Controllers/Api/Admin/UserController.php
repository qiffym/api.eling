<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\DetailUserResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Family;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = auth()->user();
        if (!$auth->tokenCan('role:admin')) {
            return $this->forbiddenResponse('Forbidden.');
        }

        $users = User::latest()->get();
        return $this->successResponse('Users retrieved successfully', UserResource::collection($users));
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
            $input = $request->validate([
                'name' => 'required|max:255',
                'role' => 'required|in:1,2,3,4,5',
                'gender' => 'in:L,P',
                'username' => 'required|unique:users,username|max:50|min:3',
                'email' => 'email:rfc,dns',
                'password' => 'required|max:12|min:5',
            ]);
            $validatedData = Arr::except($input, 'role');

            ## create user
            $user = User::create($validatedData);
            ## assign role
            User::find($user->id)->assignRole($input['role']);
            ## create specific role
            $this->createSpecificRole($input['role'], $user->id);

            return $this->createdResponse('User has been created successfully');
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function createSpecificRole($role, $user_id)
    {
        if ($role == 3) { // teacher
            Teacher::create(['user_id' => $user_id]);
        }
        if ($role == 4) { // family
            Family::create(['user_id' => $user_id]);
        }
        if ($role == 5) { // student
            Student::create(['user_id' => $user_id]);
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
        $user = User::find($id);
        return $this->successResponse('Detail user retrieved successfully', new DetailUserResource($user));
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
        //
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
