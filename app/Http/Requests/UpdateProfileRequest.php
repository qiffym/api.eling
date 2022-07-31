<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'role' => 'required|in:2,3,4,5',
            'name' => 'required|string',
            'username' => 'required|string|min:3|unique:users,username,' . $this->id,
            'email' => 'nullable|email:rfc,dns|unique:users,email,' . $this->id,
            'gender' => 'nullable|in:L,P',
            'birthday' => 'nullable|date',
            'religion' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'address' => 'nullable|string',
            'telpon' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // if teacher
            'nik' => 'required_if:role,3|digits:16',
            'nip' => 'nullable|digits:18',
            // if student
            'nis' => 'nullable|string',
            'nisn' => 'required_if:role,5|digits:10',
        ];
    }
}
