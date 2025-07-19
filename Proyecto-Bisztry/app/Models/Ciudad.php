<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Ciudad extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido

    protected $table = 'ciudades';
    protected $primaryKey = 'ciud_cod';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['ciud_cod', 'ciud_nombre', 'prov_cod'];

    // Relación: Una Ciudad pertenece a una Provincia
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'prov_cod');
    }

    // Relación: Una Ciudad puede tener muchos Clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'ciud_cod');
    }
}