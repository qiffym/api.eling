<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => str($this->type)->afterLast('\\'),
            'notification' => $this->data,
            'read_at' => $this->read_at ? $this->read_at->diffForHumans() : null,
            'created_at' => $this->created_at->isoFormat('D MMM. H:mm'),
        ];
    }
}
