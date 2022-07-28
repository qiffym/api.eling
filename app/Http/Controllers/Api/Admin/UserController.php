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
        $this->checkTokenAbility();

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
        $this->checkTokenAbility();

        try {
            $input = $request->validate([
                'name' => 'required|string',
                'role' => 'required|in:1,2,3,4,5',
                'gender' => 'in:L,P',
                'username' => 'required|unique:users,username|string|min:3',
                'email' => 'email:rfc,dns',
                'password' => 'required|string|min:5',
            ]);
            $validatedData = Arr::except($input, 'role');

            //# create user
            $user = User::create($validatedData);
            //# assign role
            User::find($user->id)->assignRole($input['role']);
            //# create specific role
            $this->attachSpecificRole($input['role'], $user->id);

            return $this->createdResponse('User has been created successfully');
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
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

        return $this->okResponse('Detail user retrieved successfully', new DetailUserResource($user));
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
        $this->checkTokenAbility();
        try {
            $request->validate([
                'name' => 'required|string',
                'role' => 'required|in:1,2,3,4,5',
                'username' => 'required|string|min:3|unique:users,username,' . $id,
                'email' => 'nullable|email:rfc,dns,' . $id,
                'gender' => 'in:L,P|nullable',
                'birthday' => 'date|nullable',
                'religion' => 'in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu|nullable',
                'address' => 'string|nullable',
                'telpon' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|nullable',
                'status' => 'required|bool',
                'password' => 'current_password|nullable',
                'new_password' => 'required_with:password|confirmed',
            ]);

            $user = User::where('id', $id);
            $currentRole = $user->first()->roles()->first()->id;

            //# update user
            $user->update([
                'name' => $request->name,
                'gender' => $request->gender,
                'username' => $request->username,
                'email' => $request->email,
                'birthday' => $request->birthday,
                'religion' => $request->religion,
                'address' => $request->address,
                'telpon' => $request->telpon,
                'status' => $request->status ?? 1,
            ]);

            //# update password
            if ($request->pasword) {
                $user->update([
                    'password' => $request->new_password,
                ]);
            }
            //# sync specific role
            if ($currentRole != $request->role) {
                $this->detachSpecificRole($currentRole, $user->first()->id);
                $this->attachSpecificRole($request->role, $user->first()->id);
            }
            //# sync role
            $user->first()->syncRoles($request->role);

            return $this->acceptedResponse('User has been updated successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
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
        $this->checkTokenAbility();
        User::find($id)->delete();

        return $this->successResponse('User has been deleted successfully');
    }

    public function checkTokenAbility()
    {
        /** @var \App\Models\User $auth * */
        $auth = auth()->user();
        if (!$auth->tokenCan('role:admin')) {
            return $this->forbiddenResponse('Forbidden.');
        }
    }

    public function attachSpecificRole($role, $user_id)
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

    public function detachSpecificRole($currentRole, $user_id)
    {
        if ($currentRole == 3) {
            Teacher::where('user_id', $user_id)->delete();
        }
        if ($currentRole == 4) {
            Family::where('user_id', $user_id)->delete();
        }
        if ($currentRole == 5) {
            Student::where('user_id', $user_id)->delete();
        }
    }
}
