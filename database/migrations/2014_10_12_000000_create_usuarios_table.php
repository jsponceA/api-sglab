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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario',100);
            $table->string('clave');
            $table->string('nombres',100);
            $table->string('apellidos',100);
            $table->string('correo')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('estado');
            $table->rememberToken();
            $table->datetimes();
            $table->softDeletesDatetime();
        });

        \App\Models\User::query()->create([
            "usuario" => "ltasayco",
            "clave" => bcrypt(123456),
            "nombres" => "luis fernando",
            "apellidos" => "tasayco tuanama",
            "correo" => "luist@gmail.com",
            "estado" => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
