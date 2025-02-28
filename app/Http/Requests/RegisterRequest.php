<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'nullable|string|min:5|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:255',
        ];
    }


    /**
     * Get the messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function messages(): array
    {
        return [
            'name.nullable' => 'Pon el nombre',
            'email.required' => 'Pon el email',
            'email.email' => 'Pon un email valido',
            'email.unique' => 'Email ya registrado',
            'password.required' => 'Contraseña requerida',
            'passsword.min' => 'Pon una ontraseña minima de 8 caracteres',
        ];
    }


}
