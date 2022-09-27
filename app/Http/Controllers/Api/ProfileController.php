<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\Users\DetailUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user = null)
    {
        try {
            $firstName = str($user->name)->words(1, '');
            $message = "$firstName's profile retrieved successfully";

            return $this->okResponse($message, new DetailUserResource($user));
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
    public function update(UpdateProfileRequest $request, User $user)
    {

        try {
            $request->validated();
            // update user
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'religion' => $request->religion,
                'address' => $request->address,
                'telpon' => $request->telpon,
                'status' => 1,
            ]);

            // update spesific role
            if ($user->hasRole('teacher')) { // teacher
                $user->teacher()->updateOrCreate(['user_id' => $user->id], ['nip' => $request->nip, 'nik' => $request->nik]);
            }

            if ($user->hasRole('student')) { // student
                $user->student()->updateOrCreate(['user_id' => $user->id], ['nis' => $request->nis, 'nisn' => $request->nisn, 'rombel_class_id' => $request->rombel]);
            }

            return $this->successResponse('Your profile has been updated successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /*
        public function updateProfileForTeacher(Request $request, User $user)
        {
            abort_if(!$user->hasRole(3), 403, 'Forbidden.');

            try {
                $request->validate([
                    'name' => 'required|string',
                    'username' => 'required|string|min:3|unique:users,username,' . $this->id,
                    'email' => 'nullable|email:rfc,dns|unique:users,email,' . $this->id,
                    'gender' => 'nullable|in:L,P',
                    'birthday' => 'nullable|date',
                    'religion' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
                    'address' => 'nullable|string',
                    'telpon' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                    'nik' => 'required_if:role,3|digits:16',
                    'nip' => 'nullable|digits:18',
                ]);

                $$user->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'gender' => $request->gender,
                    'birthday' => $request->birthday,
                    'religion' => $request->religion,
                    'address' => $request->address,
                    'telpon' => $request->telpon,
                    'status' => 1,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    */

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|current_password',
                'new_password' => 'required_with:password|confirmed',
            ]);

            $user = $request->user();
            $user->password = $request->new_password;
            $user->save();

            // logout
            User::where('id', $user->id)->update(['last_login' => now()]);
            $user->tokens()->delete();

            return $this->successResponse('Your password has been updated successfully, please login again with your new password');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|mimes:jpg,bmp,png|image|max:5120',
            ]);

            $user = $request->user();

            // store image
            $path = $request->file('avatar')->storeAs('avatars', time() . '-' . str($user->name)->slug() . '.' . $request->file('avatar')->extension());

            // cek & delete current avatar
            if ($user->avatar != null) {
                Storage::delete($user->avatar);
            }

            $user->avatar = $path;
            $user->save();

            return $this->successResponse('Your avatar has been updated successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
