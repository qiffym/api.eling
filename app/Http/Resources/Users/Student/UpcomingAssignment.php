<?php

namespace App\Http\Resources\Users\Student;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UpcomingAssignment extends JsonResource
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
            'deadline_tanggal' => Carbon::parse($this->deadline)->isoFormat('dddd, D MMM Y'),
            'deadline_jam' => Carbon::parse($this->deadline)->isoFormat('H:mm'),
            'created_at' => $this->created_at->diffForHumans(),
            'content' => [
                'id' => $this->content->id,
                'title' => $this->content->title,
            ],
            'online_class' => [
                'id' => $this->content->online_class->id,
                'name' => $this->content->online_class->name,
            ],
        ];
    }
}
