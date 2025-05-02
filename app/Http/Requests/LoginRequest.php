<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'=> 'required|email:rfc',
            'password'=> 'required|between:8,20'
        ];
    }

    public function messages()
    {
        return [
            'password.required'=> 'You must enter a valid password',
            'password.between'=> 'Password should be between 8 and 20 characters',
            'email.required'=>'You must enter a valid email',
            'email.email'=> 'Email format is not valid',
        ];
    }
}
