<?php

namespace App\Http\Requests\Shell;

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
            'artisanCmd'=> 'required',
            'project'=>'required|string'
        ];
    }
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['project'] = $this->route('project');
        return $data;
    }

    //IF MIDDLEWARE WORKS DELETE THIS
    /*
    *
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            //! AR GALIMAS PRIEJIMAS PRIE PROJEKTO DABARTINIAM USERIUJ
                if (null) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }
}
