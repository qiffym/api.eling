<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // admin
        if ($this->hasRole(2)) {
            return [
                'id' => $this->id,
                'avatar' => $this->avatar ?? $this->gravatar(),
                'name' => $this->name,
                'gender' => $this->gender,
                'username' => $this->username,
                'email' => $this->email,
                'verified' => ($this->email_verified_at) ? true : false,
                'religion' => $this->religion,
                'address' => $this->address,
                'role' => [
                    'name' => $this->getRoleNames()->first(),
                ],
            ];
        }

        // guru
        if ($this->hasRole(3)) {
            return [
                'id' => $this->id,
                'avatar' => $this->avatar ?? $this->gravatar(),
                'name' => $this->name,
                'gender' => $this->gender,
                'username' => $this->username,
                'email' => $this->email,
                'verified' => ($this->email_verified_at) ? true : false,
                'religion' => $this->religion,
                'address' => $this->address,
                'role' => [
                    'name' => $this->getRoleNames()->first(),
                    'nik' => $this->teacher->nik,
                    'nik' => $this->teacher->nip,
                ],
            ];
        }

        // family
        if ($this->hasRole(4)) {
            return [
                'id' => $this->id,
                'avatar' => $this->avatar ?? $this->gravatar(),
                'name' => $this->name,
                'gender' => $this->gender,
                'username' => $this->username,
                'email' => $this->email,
                'verified' => ($this->email_verified_at) ? true : false,
                'religion' => $this->religion,
                'address' => $this->address,
                'role' => [
                    'name' => $this->getRoleNames()->first(),
                ],
            ];
        }

        // student
        if ($this->hasRole(5)) {
            return [
                'id' => $this->id,
                'avatar' => $this->avatar ?? $this->gravatar(),
                'name' => $this->name,
                'gender' => $this->gender,
                'username' => $this->username,
                'email' => $this->email,
                'verified' => ($this->email_verified_at) ? true : false,
                'religion' => $this->religion,
                'address' => $this->address,
                'role' => [
                    'name' => $this->getRoleNames()->first(),
                    'nis' => $this->student->nis,
                    'nisn' => $this->student->nisn,
                ],
                'family' => [
                    'name' => $this->student->family->user->name ?? null,
                ]
            ];
        }
    }
}
