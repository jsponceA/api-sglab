<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Empresa extends Model
{
    protected $connection = "mysql";
    protected $table = "empresas";
    protected $primaryKey = "id";

    protected $fillable = [
        "codigo",
        "nombres",
        "imagen_cabecera",
        "imagen_pie_pagina",
        "estado",
    ];

    protected $appends = [
        "imagen_cabecera_url",
        "imagen_pie_pagina_url"
    ];

    protected function imagenCabeceraUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->imagen_cabecera) ? Storage::url("empresas/{$this->imagen_cabecera}") : null,
        );
    }

    protected function imagenPiePaginaUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->imagen_pie_pagina) ? Storage::url("empresas/{$this->imagen_pie_pagina}") : null,
        );
    }
}
