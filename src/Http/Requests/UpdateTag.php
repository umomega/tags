<?php

namespace Umomega\Tags\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTag extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|array|min:1',
            'name.*' => 'required|max:255',
            'type' => 'nullable|max:255'
        ];
    }
}