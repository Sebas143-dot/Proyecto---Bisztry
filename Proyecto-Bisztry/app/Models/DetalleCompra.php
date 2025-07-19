<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class DetalleCompra extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido

    protected $table = 'detalles_compras';
    protected $primaryKey = 'det_con_id';

    protected $fillable = [
        'comp_id',
        'var_id',
        'comp_cantidad',
        'comp_precio_unit',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'comp_id');
    }

    public function variante()
    {
        return $this->belongsTo(VarianteProd::class, 'var_id');
    }
}