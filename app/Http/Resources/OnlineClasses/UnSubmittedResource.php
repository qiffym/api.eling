<?php

namespace App\Http\Resources\OnlineClasses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UnSubmittedResource extends JsonResource
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
            'user_id' => $this->user_id,
            'student_id' => $this->id,
            'name' => $this->user->name,
            'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : $this->user->gravatar(),
            'nis' => $this->nis,
            'nisn' => $this->nisn,
            'submission' => $this->whenPivotLoaded('submission', fn () => [
                'file' => $this->pivot->file ? Storage::url($this->pivot->file) : null,
                'submitted_at' => $this->pivot->submitted_at ?? null,
                'score' => $this->pivot->score ?? null,
            ])
        ];
    }
}
