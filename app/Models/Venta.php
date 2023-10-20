<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = "ventas";
    protected $primaryKey = "id";

    protected $fillable = [
        "documento",
        "vendedor",
        "fecha",
        "nro_doc",
        "cliente",
        "cantidad",
        "u_medida",
        "codigo_producto",
        "producto",
        "moneda",
        "precio_publico",
        "precio",
        "totales",
        "descuento",
        "por_entregar",
        "um_por_entregar",
        "estado",
        "condicion_pago",
        "linea_padre",
        "linea_hijo",
    ];

}
