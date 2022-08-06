<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MotivationalWordResource extends JsonResource
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
            'title' => str($this->title)->title() ?? '',
            'body' => strip_tags($this->body),
            'from' => str($this->from)->title() ?? '',
            'active' => ($this->active == 1) ? true : false,
            'created_at' => $this->created_at->isoFormat('dddd, D MMMM Y')
        ];
    }
}
