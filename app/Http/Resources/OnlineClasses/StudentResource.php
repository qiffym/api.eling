<?php

namespace App\Http\Resources\OnlineClasses;

use App\Models\StudentAssignment;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudentResource extends JsonResource
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
            'name' => $this->user->name,
            'username' => $this->user->username,
            'email' => $this->user->email,
            'nis' => $this->nis,
            'nisn' => $this->nisn,
            'join_at' => $this->pivot->created_at->diffForHumans(),
            'assignment' => $this->whenPivotLoaded(new Submission(), fn () => [
                'status' => $this->pivot->status->name,
                'grading_status' => $this->when(is_null($this->pivot->score) && $this->pivot->status_id === 2, 'Menunggu untuk dinilai.'),
                'file' => $this->pivot->file ? Storage::url($this->pivot->file) : 'Tidak ada.',
                'submitted_at' => $this->pivot->submitted_at ? Carbon::parse($this->pivot->submitted_at)->diffForHumans() : 'Belum mengumpulkan.',
                'score' => $this->pivot->score ?? '--',
            ])
        ];
    }
}
