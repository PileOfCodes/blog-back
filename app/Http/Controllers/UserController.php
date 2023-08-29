<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function update(Request $request, User $user) {
        $validate = Validator::make($request->all(), [
            'image' => 'nullable:image'
        ]);
        if($validate->fails()) {
            return $this->errorResponse($validate->messages(), 422);
        }
        DB::beginTransaction();
        if($request->has('image')) {
            $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('images/profile', $imageName, 'public');
        }
        $user->update([
            'image' => $request->has('image') ? $imageName : $user->image
        ]);
        DB::commit();
        return $this->successResponse('update user', 200, new UserResource($user));
    }
}
