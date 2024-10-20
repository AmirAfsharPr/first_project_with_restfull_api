<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewHallSeatRequest extends FormRequest
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
            'seats' => ['required', 'array'],
            'seats.*.seat_class_id' => ['required','exists:seat_classes,id'],
            'seats.*.seat_count' => ['required','integer','gte:10','lte:1000'],
            'seats.*.unit_cost' => ['required','integer','gte:30000','lte:1000000'],
        ];
    }
}
