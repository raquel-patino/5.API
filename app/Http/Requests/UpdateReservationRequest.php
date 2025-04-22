<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
                'check_in' => 'nullable|date|after_or_equal:today',
                'check_out' => 'nullable|date|after:check_in',
                'number_guests' => 'nullable|integer|min:1|max:3',
                'hotel_id' => 'nullable|exists:hotels,id',
                'room_id' => 'nullable|exists:rooms,id',
            ];      
        }
    
    public function messages()
    {
        return [
            'check_in.date' => 'Check-in date must be a valid date.',
            'check_in.after_or_equal' => 'Check-in date must be today or later.',
            'check_out.date' => 'Check-out date must be a valid date.',
            'check_out.after' => 'Check-out date must be after check-in date.',
            'number_guests.integer' => 'Number of guests must be an integer.',
            'number_guests.min' => 'Number of guests must be at least 1.',
            'number_guests.max' => 'Number of guests cannot exceed 3.',
            'hotel_id.exists' => 'Hotel ID must exist in the hotels table.',
            'room_id.exists' => 'Room ID must exist in the rooms table.',
        ];
    }
        
    }

