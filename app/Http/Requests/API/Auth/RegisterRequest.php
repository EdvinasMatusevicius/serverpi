<?php
declare(strict_types=1);
namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class RegisterRequest extends LoginRequest
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
            'name' => ['required', 'unique:users','unique:admins', 'regex:/^[a-zA-Z0-9]+$/', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users','unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function getData(): array
    {
        return [
            'name' => $this->getUserName(),
            'email' => $this->getEmail(),
            'password' => $this->getUserPassword(),
        ];
    }

    /**
     * @return string
     */
    private function getUserName(): string
    {
        return $this->input('name');
    }

    /**
     * @return string
     */
    private function getUserPassword(): string
    {
        return bcrypt($this->input('password'));
    }
}
