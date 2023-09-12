<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
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
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse(['کاربر مورد نظر پیدا نشد'], 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse(['پسورد اشتباه است'], 422);
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

    public function changeProfile(Request $request) {
        $validate = Validator::make($request->all(), [
            'image' => 'required',
            'name' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'email' => 'nullable|email|unique:users'
        ]);
        if($validate->fails()) {
            return $this->errorResponse($validate->messages(), 422);
        }

        $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
        $request->image->storeAs('images/profile', $imageName, 'public');

        DB::beginTransaction();
        $user = User::where('id', auth()->user()->id)->update([
            'name' => $request->name == null || $request->name == '' ? auth()->user()->name : $request->name, 
            'email' => $request->email == null || $request->email == '' ? auth()->user()->email : $request->email, 
            'password' => $request->password == null || $request->password == '' ? auth()->user()->password : bcrypt($request->password), 
            'image' => $request->image == null || $request->image == '' ? auth()->user()->image : $imageName 
        ]);
        DB::commit();
        return $this->successResponse('user profile', 200, new UserResource($user));
    }
}
