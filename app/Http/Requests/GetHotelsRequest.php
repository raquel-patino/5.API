<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetHotelsRequest extends FormRequest
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
            'check_in'=>'required|date|after_or_equal:today',   
            'check_out'=>'required|date|after:check_in',
            'place'=>'string|nullable',
        ];
    }

    public function messages()
    {
        return [
            'place.string' => 'Place must be a string.',
            'check_in.required' => 'Check-in date is required.',
            'check_in.date' => 'Check-in date must be a valid date.',
            'check_in.after_or_equal' => 'Check-in date must be today or later.',
            'check_out.required' => 'Check-out date is required.',
            'check_out.date' => 'Check-out date must be a valid date.',
            'check_out.after' => 'Check-out date must be after check-in date.',
        ];
    }
}
