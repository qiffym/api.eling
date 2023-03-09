<?php

namespace App\Http\Resources\Submissions;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'content_of' => $this->content->title,
            'author' => $this->content->online_class->teacher->user->name,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'submission' => [
                'status' => $this->pivot->status->name,
                'grading_status' => $this->when(is_null($this->pivot->score) && $this->pivot->status_id === 2, 'Menunggu untuk dinilai.'),
                'file' => $this->pivot->file ? Storage::url($this->pivot->file) : 'Tidak ada.',
                'filename' => $this->when(!is_null($this->pivot->file), Str::of($this->pivot->file), '--'),
                'submitted_at' => $this->pivot->submitted_at ? Carbon::parse($this->pivot->submitted_at)->diffForHumans() : '--',
                'score' => $this->pivot->score ?? '--',
            ],
        ];
    }
}
