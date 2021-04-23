<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $default = parent::toArray($request);
        
        $extra = [
            'cover_image' => asset('storage/' . $this->cover_image),
            'user' => new UserResource($this->user),
            'edit_url' => route('dashboard.post_edit', [$this->id]),
            'delete_url' => route('dashboard.post_delete', [$this->id]),
        ];
        return array_merge($default, $extra);
    }
}
