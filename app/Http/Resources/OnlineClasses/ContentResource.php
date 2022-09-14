<?php

namespace App\Http\Resources\OnlineClasses;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
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
            'online_class_id' => $this->online_class_id,
            'rombel' => $this->online_class->rombel_class->name,
            'online_class' => $this->online_class->name,
            'title' => $this->title,
            'description' => $this->desc,
            'created_at' => $this->created_at->isoFormat('dddd, D MMMM Y'),
            'updated_at' => $this->updated_at->diffForHumans(),
            'materials' => MaterialResource::collection($this->materials),
            'forums' => ForumResource::collection($this->forums),
            'assignment' => AssignmentResource::collection($this->assignments),

        ];
    }
}
