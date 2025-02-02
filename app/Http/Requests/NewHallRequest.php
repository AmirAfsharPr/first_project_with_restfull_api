<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewHallRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:4','unique:halls,name'],
            'seat_count' => ['required', 'integer', 'gte:50', 'lte:100000'],
        ];
    }
}
