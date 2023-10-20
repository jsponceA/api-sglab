<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('documento',100)->nullable();
            $table->string('vendedor',100)->nullable();
            $table->date('fecha')->nullable();
            $table->string('nro_doc',100)->nullable();
            $table->string('cliente',100)->nullable();
            $table->decimal('cantidad',11,2)->nullable();
            $table->string('u_medida',100)->nullable();
            $table->string('codigo_producto',100)->nullable();
            $table->string('producto',255)->nullable();
            $table->string('moneda',50)->nullable();
            $table->decimal('precio_publico',11,2)->nullable();
            $table->string('precio',100)->nullable();
            $table->string('totales',100)->nullable();
            $table->string('descuento',100)->nullable();
            $table->string('por_entregar',100)->nullable();
            $table->string('um_por_entregar',100)->nullable();
            $table->string('estado',100)->nullable();
            $table->string('condicion_pago',100)->nullable();
            $table->string('linea_padre',100)->nullable();
            $table->string('linea_hijo',100)->nullable();
            $table->datetimes();
            $table->softDeletesDatetime();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
