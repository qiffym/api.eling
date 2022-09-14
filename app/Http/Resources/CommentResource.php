<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'avatar' => $this->author->avatar ? asset('storage/' . $this->author->avatar) : $this->author->gravatar(),
            'author' => $this->author->name,
            'comment' => $this->comment,
            'edited' => ($this->edited == 1) ? true : false,
            'created_at' => $this->created_at->diffForHumans(),
            'sub_comments' => SubCommentResource::collection($this->subComments)
        ];
    }
}
