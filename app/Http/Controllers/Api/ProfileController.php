<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\Users\DetailUserResource;
use App\Models\Student;
use App\Models\Teacher;
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
    public function show($id)
    {
        $user = User::find($id);

        $firstName = str($user->name)->words(1, '');
        $message = "$firstName's profile retrieved successfully";

        return $this->okResponse($message, new DetailUserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request, $id)
    {
        $request->validated();

        try {
            // update user
            $user = User::where('id', $id);
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'religion' => $request->religion,
                'address' => $request->address,
                'telpon' => $request->telpon,
                'status' => 1
            ]);

            // update spesific role
            if ($request->role == 3) { // teacher
                Teacher::where('user_id', $id)->update(['nik' => $request->nik, 'nip' => $request->nip]);
            }
            if ($request->role == 5) { // student
                Student::where('user_id', $id)->update(['nis' => $request->nis, 'nisn' => $request->nisn]);
            }

            return $this->successResponse('Your profile has been updated successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|current_password',
            'new_password' => 'required_with:password|confirmed'
        ]);

        $user = User::find($id);
        $user->password = $request->new_password;
        $user->save();

        return $this->successResponse('Your password has been updated successfully');
    }

    public function updateAvatar(Request $request, $id)
    {
        $request->validate([
            'avatar' => 'required|mimes:jpg,bmp,png|image|max:5120'
        ]);

        $user = User::find($id);

        // store image
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->storeAs('avatars', now() . '-' . $user->name . '.' . $request->file('avatar')->extension());
        } else {
            $path = '';
        }

        // cek & delete current avatar
        if ($user->avatar != null) {
            Storage::delete($user->avatar);
        }

        $user->avatar = $path;
        $user->save();

        return $this->successResponse('Your avatar has been updated successfully');
    }
}
