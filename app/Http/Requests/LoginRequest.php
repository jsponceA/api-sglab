<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "usuario" => ["required","max:255"],
            "clave" => ["required","max:255"],
            "tipoUsuario" => ["required","in:paciente,medico,empresa,administrador"]
        ];
    }
}
