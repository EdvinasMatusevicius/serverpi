<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'applicationName'=>'required|string|regex:/^[a-zA-Z0-9]+$/|unique:applications|unique:admin_applications|min:3|max:60',
            'giturl'=>'required|min:23|url'
        ];

    }
    public function getData(): array
    {
        $data = [
            'applicationName' => $this->input('applicationName'),
            'giturl' => $this->input('giturl'),
        ];

        return $data;
    }

}
