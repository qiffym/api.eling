<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'avatar' => $this->avatar ?? $this->gravatar(),
            'name' => $this->name,
            'role' => $this->getRoleNames()->first(),
            'username' => $this->username,
            'email' => $this->email,
            'verified' => ($this->email_verified_at) ? true : false,
            'gender' => $this->gender,
        ];
    }
}
