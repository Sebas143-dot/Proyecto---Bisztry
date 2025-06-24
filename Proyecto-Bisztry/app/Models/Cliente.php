<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'clie_id';

    protected $fillable = [
        'clie_nombre',
        'clie_apellido',
        'clie_email',
        'clie_telefono',
        'ciud_cod',
        'clie_direccion',
        'clie_fecha_nac',
    ];

    // Relación: Un Cliente pertenece a una Ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciud_cod');
    }

    // Relación: Un Cliente puede tener muchos Pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'clie_id');
    }
}