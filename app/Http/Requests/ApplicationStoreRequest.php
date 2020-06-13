<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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
            'applicationName'=>'required|string|regex:/(?! )^([a-z0-9 ])+(?<! )$/|unique:applications|unique:admin_applications|min:3|max:60',
            'applicationSlug'=>'nullable|string|regex:/(?!-)^([a-z0-9-])+(?<!-)$/|unique:applications,slug|unique:admin_applications,slug|min:3|max:60',
            'giturl'=>'required|min:23|url',
            'language'=>'required|integer|max:3',
            'database'=>'sometimes|regex:/(?!-)^([a-z0-9-])+(?<!-)$/|max:60'
        ];

    }
    public function getData(): array
    {
        $data = [
            'applicationName' => $this->input('applicationName'),
            'slug'=>$this->getSlug(),
            'giturl' => $this->input('giturl'),
            'language'=>$this->input('language')
        ];

        if($this->input('database')!=null){
            $data['database']=$this->input('database');
        }

        return $data;
    }
    protected function getSlug():string
    {
        $slug = $this->input('applicationSlug');

        if ($slug == null) {
            $slug = $this->input('applicationName');
        }

        return Str::slug($slug);
    }

}
