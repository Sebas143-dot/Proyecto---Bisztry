<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MetodoPago extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido
    
    protected $table = 'metodos_pago';
    protected $primaryKey = 'meto_cod';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['meto_cod', 'medo_detale'];
}