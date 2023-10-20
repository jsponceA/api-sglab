<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Archivo extends Model
{
    protected $table = "archivos";
    protected $primaryKey = "id";

    protected $fillable = [
        "usuario_id",
        "archivo"
    ];

    protected $appends = [
        "excel_url"
    ];

    public function usuario(): HasOne
    {
        return $this->hasOne(User::class,"id","usuario_id");
    }

    public function getExcelUrlAttribute()
    {
        return !empty($this->archivo) ? Storage::url("archivos/".$this->archivo) : null;
    }
}
