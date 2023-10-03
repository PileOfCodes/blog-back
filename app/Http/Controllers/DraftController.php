<?php

namespace App\Http\Controllers;

use App\Http\Resources\DraftResource;
use App\Models\Draft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class DraftController extends ApiController
{
    public function index() {
        $drafts = Draft::paginate(10);
        return $this->successResponse('all drafts', 200, [
            'drafts' => DraftResource::collection($drafts),
            'links' => DraftResource::collection($drafts)->response()->getData()->links,
            'meta' => DraftResource::collection($drafts)->response()->getData()->meta
        ]);

    }

    public function show(Draft $draft) {
        return $this->successResponse('draft', 200, new DraftResource($draft));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        $draft = Draft::create([
            'title' => $request->title,
            'content' => $request->content,
            'link' => Str::random(16),
            'user_id' => auth()->user()->id
        ]);
        return $this->successResponse('draft created successfully', 201, new DraftResource($draft));
    }

    public function update(Request $request, Draft $draft) {
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        $draft->update([
            'title' => $request->title,
            'content' => $request->content
        ]);
        return $this->successResponse('draft updated successfully', 200, new DraftResource($draft));
    }

    public function destroy(Draft $draft) {
        DB::beginTransaction();
        $draft->delete();
        DB::commit();
        return $this->successResponse('draft is deleted successfully', 200, new DraftResource($draft));
    }
}
