<?php

namespace App\Http\Controllers;

use App\Http\Resources\SliderResource;
use App\Models\Post;
use Illuminate\Http\Request;

class SliderController extends ApiController
{
    public function allSliders() {
        $sliders = Post::orderBy('created_at', 'desc')->inRandomOrder->take(5)->get();
        return $this->successResponse('get sliders', 200, SliderResource::collection($sliders));
    }
}
