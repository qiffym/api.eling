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
                Rule::requiredIf($this->user->hasRole('teacher')),
                Rule::prohibitedIf(!$this->user->hasRole('teacher')),
                'digits:16',
            ],
            'nip' => [
                Rule::prohibitedIf(!$this->user->hasRole('teacher')),
                'nullable', 'digits:18',
            ],

            // if student
            'nis' => [
                // Rule::requiredIf($this->user->hasRole('student')),
                Rule::prohibitedIf(!$this->user->hasRole('student')),
                'regex:/^[0-9]+$/',
            ],
            'nisn' => [
                Rule::prohibitedIf(!$this->user->hasRole('student')),
                'nullable', 'digits:10',
            ],
            'rombel' => [
                Rule::requiredIf($this->user->hasRole('student')),
                Rule::prohibitedIf(!$this->user->hasRole('student')),
                'exists:rombel_classes,id',
            ],
        ];
    }
}
