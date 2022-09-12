<?php

namespace App\Http\Resources\Submissions;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SubmissionRecource extends JsonResource
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
            'assignment_id' => $this->id,
            'content' => $this->content->title,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'submission' => [
                'status' => $this->pivot->status->name,
                'submitted_at' => $this->pivot->submitted_at ? Carbon::parse($this->pivot->submitted_at)->diffForHumans() : '--',
                'file' => $this->pivot->file ? Storage::url($this->pivot->file) : '--',
                'score' => $this->pivot->score ?? '--'
            ]
        ];
    }
}
