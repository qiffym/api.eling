<?php

namespace App\Http\Resources\OnlineClasses;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionForumResource extends JsonResource
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
            'content_id' => $this->content->id,
            'content_of' => $this->content->title,
            'topic' => $this->title,
            'description' => $this->description,
            'total_comment' => $this->comments->count(),
            'comments' => $this->whenLoaded('comments', CommentResource::collection($this->comments)),
        ];
    }
}
