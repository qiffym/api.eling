<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avatar' => $this->author->avatar ? asset('storage/' . $this->author->avatar) : $this->author->gravatar(),
            'author' => $this->author->name,
            'comment' => $this->comment,
            'edited' => ($this->edited == 1) ? true : false,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
