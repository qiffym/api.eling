<?php

namespace App\Http\Resources\OnlineClasses;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'join_at' => $this->pivot->created_at->diffForHumans()
        ];
    }
}
