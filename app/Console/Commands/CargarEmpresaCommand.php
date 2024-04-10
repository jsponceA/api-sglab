<?php

namespace App\Console\Commands;

use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CargarEmpresaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cargar-empresas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'comando para cargar nuevas empresas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $actualesEmpresas = Empresa::query()->pluck("codigo")->toArray();
        $todasLasEmpresas = DB::select("SET NOCOUNT ON; EXEC web_listaempresas");
        $nuevasEmpresas = collect($todasLasEmpresas)->whereNotIn("codigo",$actualesEmpresas);

        foreach ($nuevasEmpresas as $nuevaEmpresa) {
            Empresa::query()->create([
                "codigo" => $nuevaEmpresa->codigo,
                "nombres" => trim($nuevaEmpresa->nombre),
                "estado" => 1
            ]);
        }

        $this->info("Registros nuevos ingresados: {$nuevasEmpresas->count()}");
    }

}
