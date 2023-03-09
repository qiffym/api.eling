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

        if (!Auth::guard('web')->attempt([$credType => $creds['username'], 'password' => $creds['password']])) {
            return $this->unauthenticatedResponse('The username or password incorrect.');
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
            $token = $user->createToken('Eling', ['admin'])->accessToken;
        }
        if ($user->hasRole('teacher')) {
            $token = $user->createToken('Eling', ['teacher'])->accessToken;
        }
        if ($user->hasRole('student')) {
            $token = $user->createToken('Eling', ['student'])->accessToken;
        }

        return $this->okResponse('You have successfully logged in', ['user' => new AuthResource($user), 'token' => $token]);
    }
}
