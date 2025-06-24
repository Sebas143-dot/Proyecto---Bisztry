<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    protected $primaryKey = 'prov_ruc';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'prov_ruc',
        'prov_nombre',
        'prov_contacto',
        'prov_telefono',
        'prov_email',
    ];

    // Relación: Un Proveedor puede tener muchas Compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'prov_ruc');
    }
}