<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'englishTitle' => $this->englishTitle,
            'persianTitle' => $this->persianTitle,
            'image' => url(env('POST_IMAGE') . $this->image),
            'englishDate' => Carbon::parse($this->eventTime)->isoFormat('MMMM Do YYYY'),
            'persianDate' => verta($this->eventTime)->format('%B %d، %Y'),
        ];
    }
}
