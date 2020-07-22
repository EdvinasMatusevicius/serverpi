<?php
declare(strict_types=1);

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|exists:users',
            'password' => 'required|string|min:8',
        ];
    }
    public function getCredentials(): array
    {
        return $this->only(['email', 'password']);
    }
    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->input('email');
    }
}
