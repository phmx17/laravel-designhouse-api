<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
        'id' => $this->id,
        'user' => new UserResource($this->user),
        'title' => $this->title,
        'slug' => $this->slug,
        'images' => $this->images,  // array of all 3 sizes (paths) see getImagesAttribute() in Design model
        'is_Live' => $this->is_live,
        'description' => $this->description,
        'tag_list' => [ // using Cviebrock taggable package; for normalized see \config\taggable
          'tags' => $this->tagArray,
          'normalized' => $this->tagArrayNormalized
        ],
        'created_at_dates' => [
          'created_at_human' => $this->created_at->diffForhumans(),
          'created_at' => $this->created_at
        ],
        'updated_at_dates' => [
          'updated_at_human' => $this->created_at->diffForhumans(),
          'updated_at' => $this->created_at
        ],
      ];
    }
}
