<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class VarianteProd extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido
    
    protected $table = 'variantes_prod';
    protected $primaryKey = 'var_id';
    
    protected $fillable = [
        'talla_cod',
        'color_id',
        'prod_cod',
        'sku',
        'var_stok_actual',
        'var_stock_min',
        'var_precio',
    ];

    // Relación: Una Variante pertenece a un Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'prod_cod');
    }

    // Relación: Una Variante pertenece a una Talla
    public function talla()
    {
        return $this->belongsTo(Talla::class, 'talla_cod');
    }

    // Relación: Una Variante pertenece a un Color
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}