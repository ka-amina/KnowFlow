<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = Category::find($this->category_id);
        return [

            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'level' => $this->level,
            'status' => $this->status,
            'category' => [
                'id' => $this->category_id,
                'name' => $category->name
            ],
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
