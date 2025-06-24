<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'comp_id';

    protected $fillable = [
        'prov_ruc',
        'comp_feccha',
        'comp_precio_total',
        'comp_factura_num',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'prov_ruc');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'comp_id');
    }
}