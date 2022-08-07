<?php

namespace App\Http\Resources\OnlineClasses;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailOnlineClassResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->desc,
            'created_at' => $this->created_at->isoFormat('dddd, D MMMM Y'),
            'updated_at' => $this->updated_at->diffForHumans(),
            'class' => $this->rombel_class->name,
            'department' => $this->rombel_class->department->name,
            'teacher' => [
                'id' => $this->teacher->id,
                'name' => $this->teacher->user->name,
                'nik' => $this->teacher->nik,
                'nip' => $this->teacher->nip,
            ],
            'students' => [
                'total' => $this->students->count(),
                'data' => StudentResource::collection($this->students()->orderBy('pivot_created_at', 'desc')->get())
            ]
        ];
    }
}
