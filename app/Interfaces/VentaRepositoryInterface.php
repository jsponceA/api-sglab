<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface VentaRepositoryInterface
{
    public function todasLasVentas(array $params);
}
