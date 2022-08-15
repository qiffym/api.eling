<?php

namespace App\Http\Resources\Users;

use Carbon\Carbon;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => str($this->getRoleNames()->first())->title(),
            'gender' => $this->gender,
            'username' => $this->username,
            'email' => $this->email,
            'verified' => ($this->email_verified_at) ? true : false,
            'birthday' => ($this->birthday) ? Carbon::parse($this->birthday)->isoFormat('dddd, D MMMM Y') : null,
            'religion' => $this->religion,
            'address' => $this->address,
            'status' => ($this->status == 1) ? 'Active' : 'Deactive',
            'teacher' => $this->when($this->hasRole(3), new TeacherResource($this->teacher)),
            'student' => $this->when($this->hasRole(5), new StudentResource($this->student)),
            'family' => $this->when($this->hasRole(4), $this->family),
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : $this->gravatar(),
        ];
    }
}
