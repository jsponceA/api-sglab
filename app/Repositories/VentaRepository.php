<?php

namespace App\Repositories;

use App\Interfaces\VentaRepositoryInterface;
use App\Models\Venta;

class VentaRepository implements VentaRepositoryInterface
{
    public function todasLasVentas(array $params)
    {
        $buscar = $params["buscar"] ?? null;
        $cantidadRegistros = $params["cantidadRegistros"] ?? null;
        $pagina = $params["pagina"] ?? null;

        $ventas = Venta::query()
            ->when(!empty($buscar),function ($query) use ($buscar){
                $query
                    ->where("documento","ILIKE","%".$buscar."%")
                    ->orWhere("nro_doc","ILIKE","%".$buscar."%");
            })
            ->orderBy("id","DESC")
            ->paginate($cantidadRegistros,"*","page",$pagina);

        return $ventas;
    }



}
