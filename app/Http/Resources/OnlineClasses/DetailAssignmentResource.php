<?php

namespace App\Http\Resources\OnlineClasses;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailAssignmentResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => Carbon::parse($this->deadline)->diffForHumans(),
            'created_at' => $this->created_at->diffForHumans(),
            'submission' => $this->when($request->user()->hasRole('teacher'), StudentResource::collection($this->students)),
        ];
    }
}
