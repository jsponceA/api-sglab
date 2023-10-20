<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        switch ($this->getMethod()){
            case "POST":
                return [
                    "usuario" => ["required","max:100",Rule::unique("usuarios","usuario")->whereNull("deleted_at")],
                    "clave" => ["required","max:255"],
                    "nombres" => ["required","max:100"],
                    "apellidos" => ["required","max:100"],
                    "correo" => ["required","email","max:255"],
                    "foto" => ["nullable","image","max:8192"],
                    "estado" => ["required"],
                ];
            case "PUT":
                return [
                    "usuario" => ["required","max:100",Rule::unique("usuarios","usuario")->ignore($this->id,"id")->whereNull("deleted_at")],
                    "clave" => ["nullable","max:255"],
                    "nombres" => ["required","max:100"],
                    "apellidos" => ["required","max:100"],
                    "correo" => ["required","email","max:255"],
                    "foto" => ["nullable","image","max:8192"],
                    "estado" => ["required"],
                ];
            default:
                return [];
        }
    }
}
