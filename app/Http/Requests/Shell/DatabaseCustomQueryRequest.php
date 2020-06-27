<?php

namespace App\Http\Requests\Shell;

use Illuminate\Foundation\Http\FormRequest;

class DatabaseCustomQueryRequest extends DatabaseCreateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
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
            'password'=>['required','string','min:4'],
            'project'=>['required','string'],
            'customquery'=>['required','string']
        ];
    }
}
