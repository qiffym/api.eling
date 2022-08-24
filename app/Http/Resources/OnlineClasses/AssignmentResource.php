<?php

namespace App\Http\Resources\OnlineClasses;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'submission' => $this->when($request->user()->hasRole(3), StudentResource::collection($this->students)),
        ];
    }
}
