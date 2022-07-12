<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'location_id' => 'required',
            'title' => 'required|max:100',
            'description' => 'max:16000',
            'tickets_count' => 'required|integer|min:5',
            'start_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:start_date'
        ];
    }
}
