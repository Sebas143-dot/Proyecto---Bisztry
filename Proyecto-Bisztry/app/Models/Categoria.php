<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Añadido para auditoría
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Categoria extends Model implements AuditableContract // <-- Añadido
{
    use HasFactory;
    use Auditable; // <-- Añadido
    
    protected $table = 'categorias';
    protected $primaryKey = 'cate_id';
    
    protected $fillable = ['cate_detalle'];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'cate_id');
    }
}