<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewConcertRequest extends FormRequest
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
            'artist_id' => ['required', 'exists:artists,id'],
            'title' => ['required', 'string', 'min:3', 'max:50'],
            'description' => ['required', 'string', 'min:10'],
            'starts_at' => ['required', 'date','before_or_equal:ends_at'],
            'ends_at' => ['required', 'date','after_or_equal:starts_at'],
            'is_published' => ['nullable'],
        ];
    }
}
