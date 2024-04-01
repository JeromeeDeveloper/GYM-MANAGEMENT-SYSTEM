<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $this->member,
            'phone' => 'required|string|max:20', // Adjust max length according to your needs
            // 'age' => 'required|integer|min:18', // Adjust minimum age according to your requirements
            // 'gender' => 'nullable|in:male,female', // Gender can be either male or female or null
            // 'birthdate' => 'nullable|date', // Validate as a date
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [

            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            // 'age' => 'Age',
            // 'gender' => 'Gender',
            // 'birthdate' => 'Birthdate',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
