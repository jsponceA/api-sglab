<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "usuarios";
    protected $primaryKey = "id";

    protected $fillable = [
        "usuario",
        "clave",
        "nombres",
        "apellidos",
        "correo",
        "foto",
        "estado",
    ];

    protected $appends = [
        "foto_url"
    ];

    public function getFotoUrlAttribute()
    {
        return !empty($this->foto) ? Storage::url("usuarios/".$this->foto) : null;
    }
}
