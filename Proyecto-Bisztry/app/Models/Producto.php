<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Producto extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido

    protected $table = 'productos';
    protected $primaryKey = 'prod_cod';

    protected $fillable = [
        'cate_id',
        'prod_nombre',
        'prod_estado',
    ];

    // Relación: Un Producto pertenece a una Categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'cate_id');
    }

    // Relación: Un Producto tiene muchas Variantes
    public function variantes()
    {
        return $this->hasMany(VarianteProd::class, 'prod_cod');
    }
}