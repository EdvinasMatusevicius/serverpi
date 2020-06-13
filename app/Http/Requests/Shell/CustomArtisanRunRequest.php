<?php

namespace App\Http\Requests\Shell;

use App\Repositories\ApplicationRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CustomArtisanRunRequest extends FormRequest
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
            'artisanCmd'=> ['required','string','not_regex:/[&|;]+/'],
            'project'=>['required','string'],
        ];
    }
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['project'] = $this->route('project');
        return $data;
    }

    /*
    *
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $repository = new ApplicationRepository;
            if (!$repository->applicationBelongsToUser($this->project)) {
                $validator->errors()->add('inaccessible project', 'Something is wrong with project\'s url field!');
            }
        });
    }
}
