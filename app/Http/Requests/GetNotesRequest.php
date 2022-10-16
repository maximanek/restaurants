<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetNotesRequest extends FormRequest
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
            'limit' => 'nullable | string',
            'restaurant_id' => 'nullable | int',
            'user_id' => 'nullable | int',
        ];
    }
}
