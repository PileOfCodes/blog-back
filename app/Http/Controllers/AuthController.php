<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('myApp')->plainTextToken;

        return $this->successResponse('user login',200,[
            'user' => new UserResource($user),
            'login_token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('کاربر مورد نظر پیدا نشد', 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('پسورد اشتباه است', 422);
        }

        $token = $user->createToken('myApp')->plainTextToken;

        return $this->successResponse('user login',200,[
            'user' => new UserResource($user),
            'login_token' => $token
        ]);
    }

    public function me() {
        $user = User::find(auth()->user()->id);
        return $this->successResponse('user is up', 200, new UserResource($user));
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return $this->successResponse('user logged out', 200, "");
    }
}
