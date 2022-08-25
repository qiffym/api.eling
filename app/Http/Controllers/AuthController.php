<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $creds = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$credType => $creds['username'], 'password' => $creds['password']])) {
            return $this->unauthenticatedResponse('The provided credentials do not match our records.');
        }

        return $this->response(Auth::user());
    }

    public function logout()
    {
        /** @var \App\Models\User $user * */
        $user = Auth::user();
        \App\Models\User::where('id', $user->id)->update(['last_login' => now()]);
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'You have successfully logged out',
        ]);
    }

    public function response($user)
    {
        if ($user->hasRole('admin')) {
            $token = $user->createToken('e-learning', ['role:admin'])->plainTextToken;
        } elseif ($user->hasRole('teacher')) {
            $token = $user->createToken('e-learning', ['role:teacher'])->plainTextToken;
        } elseif ($user->hasRole('family')) {
            $token = $user->createToken('e-learning', ['role:family'])->plainTextToken;
        } elseif ($user->hasRole('student')) {
            $token = $user->createToken('e-learning', ['role:student'])->plainTextToken;
        } else {
            $token = $user->createToken('e-learning', ['role:student'])->plainTextToken;
        }

        return $this->okResponse('You have successfully logged in', ['user' => new AuthResource($user), 'token' => $token]);
    }
}
