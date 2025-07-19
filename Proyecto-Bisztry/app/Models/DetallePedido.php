<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class DetallePedido extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido
    
    protected $table = 'detalles_pedidos';
    protected $primaryKey = 'det_pedi_id';

    protected $fillable = [
        'var_id',
        'pedi_id',
        'cantidad',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedi_id');
    }

    public function variante()
    {
        return $this->belongsTo(VarianteProd::class, 'var_id');
    }
}