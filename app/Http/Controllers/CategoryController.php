<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    public function index()
    {
        $categories = Category::all();
        return $this->successResponse('all categories', 200, CategoryResource::collection($categories));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'englishTitle' => 'required',
            'persianTitle' => 'required',
        ]);
        if($validate->fails()) {
            return $this->errorResponse($validate->messages(), 421);
        }
        DB::beginTransaction();
        $category = Category::create([
            'englishTitle' => $request->englishTitle,
            'persianTitle' => $request->persianTitle,
        ]);
        DB::commit();
        return $this->successResponse('category created successfully', 201, new CategoryResource($category));
    }

    public function show(Category $category)
    {
        $category = Category::find($category->id);
        return $this->successResponse("category {$category->title}", 200, new CategoryResource($category));
    }

    public function update(Request $request, Category $category)
    {
        $validate = Validator::make($request->all(), [
            'englishTitle' => 'required',
            'persianTitle' => 'required',
        ]);
        if($validate->fails()) {
            return $this->errorResponse($validate->messages(), 421);
        }
        DB::beginTransaction();
        $category->update([
            'englishTitle' => $request->englishTitle,
            'persianTitle' => $request->persianTitle,
        ]);
        DB::commit();
        return $this->successResponse('category updated successfully', 200, new CategoryResource($category));
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        $category->delete();
        DB::commit();
        return $this->successResponse('category deleted successfully', 200, new CategoryResource($category));
    }
}
