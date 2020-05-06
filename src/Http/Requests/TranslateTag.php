<?php

namespace Umomega\Tags\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslateTag extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_translation' => 'required|max:255',
            'locale' => 'required|in:' . implode(',', config('app.locales'))
        ];
    }
}