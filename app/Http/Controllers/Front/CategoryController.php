<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function allCategories() {
        $categories = Category::take(11)->get();
        return $this->successResponse('all categories', 200, CategoryResource::collection($categories));
    }
}
