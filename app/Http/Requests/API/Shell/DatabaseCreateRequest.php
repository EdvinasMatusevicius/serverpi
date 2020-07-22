<?php

namespace App\Http\Requests\API\Shell;

use Illuminate\Foundation\Http\FormRequest;

class DatabaseCreateRequest extends FormRequest
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
            'project'=>['required','string']
        ];
    }
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['project'] = $this->route('project');
        return $data;
    }
}
