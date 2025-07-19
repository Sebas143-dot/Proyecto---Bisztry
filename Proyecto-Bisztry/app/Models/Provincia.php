<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Provincia extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido

    protected $table = 'provincias';
    protected $primaryKey = 'prov_cod';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['prov_cod', 'prov_nombre']; // Asegúrate de que este es el nombre de la columna en tu BD

    // Relación: Una Provincia tiene muchas Ciudades
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'prov_cod');
    }
}