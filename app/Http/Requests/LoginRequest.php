<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Pon el nombre',
            'email.required' => 'Pon el email',
            'email.email' => 'Pon un email valido',
            'email.unique' => 'Email ya registrado',
            'password.required' => 'Contraseña requerida',
            'password.min' => 'Pon una ontraseña minima de 8 caracteres',
        ];
    }
}
