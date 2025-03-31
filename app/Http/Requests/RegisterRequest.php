<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'=> 'required|max:30',
            'surname'=> 'required|max:50',
            'username'=> 'required|unique:users',
            'email'=> 'required|email:rfc|unique:users',
            'telephone'=> 'required',
            'password'=> 'required|between:8,20|confirmed'
        ]; 
    }

    public function messages(): array
    {
        return [
            'name.required'=> 'You must enter a valid name',
            'name.max'=> 'Name is too long',
            'surname.required'=> 'You must enter a valid surname',
            'surname.max'=>'Surname is too long',
            'username.required'=> 'You must enter a valid username',
            'username.unique'=> 'This username already exists',
            'email.required'=>'You must enter a valid email',
            'email.email'=> 'Email format is not valid',
            'email.unique'=> 'This email already exists',
            'telephone.required' =>' You must enter a valid telephone',
            'password.required'=> 'You must enter a valid password',
            'password.between'=> 'Password should be between 8 and 20 characters',
            'password.confirmed'=> 'Passwords doesn´t match'
        ];
        
    }
}
