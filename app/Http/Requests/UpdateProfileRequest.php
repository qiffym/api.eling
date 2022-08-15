<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'username' => 'required|string|min:3|unique:users,username,' . $this->user->id,
            'email' => 'nullable|email|unique:users,email,' . $this->user->id,
            'gender' => 'nullable|in:L,P',
            'birthday' => 'nullable|date',
            'religion' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'address' => 'nullable|string',
            'telpon' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // if teacher
            'nik' => [
                Rule::requiredIf($this->user->hasRole(3)),
                Rule::prohibitedIf(!$this->user->hasRole(3)),
                'digits:16'
            ],
            'nip' => [
                Rule::prohibitedIf(!$this->user->hasRole(3)),
                'nullable', 'digits:18'
            ],

            // if student
            'nis' => [
                Rule::requiredIf($this->user->hasRole(5)),
                Rule::prohibitedIf(!$this->user->hasRole(5)),
                'regex:/^[0-9]+$/'
            ],
            'nisn' => [
                Rule::prohibitedIf(!$this->user->hasRole(5)),
                'nullable', 'digits:10'
            ],
        ];
    }
}
