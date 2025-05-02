<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ModifyProfileRequest extends FormRequest
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
            'name'=> 'sometimes|max:30',
            'surname'=> 'sometimes|max:50',
            'username' => 'sometimes|unique:users,username,' . Auth::id(),
            'email'=> 'sometimes|email:rfc,dns|unique:users,email,'. Auth::id(),
            'street_type' =>'nullable',
            'street_name'=>'nullable',
            'postcode'=>'nullable',
            'city'=> 'nullable',
            'country'=>'nullable',
            'telephone'=> 'sometimes',
            'password'=> 'sometimes|between:8,20|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'name.max'=> 'Name is too long',
            'surname.max'=>'Surname is too long',
            'username.unique'=> 'This username already exists',
            'email.email'=> 'Email format is not valid',
            'password.between'=> 'Password should be between 8 and 20 characters',
            'password.confirmed'=> 'Passwords doesn´t match'
        ];
    }
}
