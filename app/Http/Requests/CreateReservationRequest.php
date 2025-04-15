<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReservationRequest extends FormRequest
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
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_guests' => 'required|integer|min:1|max:3',
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required|exists:users,id',
        ];      
    }

    public function messages()
    {
        return [
            'check_in.required' => 'Check-in date is required.',
            'check_in.date' => 'Check-in date must be a valid date.',
            'check_in.after_or_equal' => 'Check-in date must be today or later.',
            'check_out.required' => 'Check-out date is required.',
            'check_out.date' => 'Check-out date must be a valid date.',
            'check_out.after' => 'Check-out date must be after check-in date.',
            'number_guests.required' => 'Number of guests is required.',
            'number_guests.integer' => 'Number of guests must be an integer.',
            'number_guests.min' => 'Number of guests must be at least 1.',
            'number_guests.max' => 'Number of guests cannot exceed 3.',
            'hotel_id.required' => 'Hotel ID is required.',
            'hotel_id.exists' => 'Hotel ID must exist in the hotels table.',
            'room_id.required' => 'Room ID is required.',
            'room_id.exists' => 'Room ID must exist in the rooms table.',
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'User ID must exist in the users table.'
        ];
    }
}
