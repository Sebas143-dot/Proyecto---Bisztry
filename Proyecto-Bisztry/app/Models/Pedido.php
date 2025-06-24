<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'pedi_id';

    protected $fillable = [
        'clie_id',
        'pedi_fecha',
        'esta_cod',
        'ciud_cod',
        'pedi_direccion',
        'pedi_fecha_envio',
        'pedi_fecha_entrega',
        'pedi_total',
        'pedi_costo_envio',
        'meto_cod',
        'serv_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clie_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'esta_cod');
    }
    
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciud_cod');
    }
    
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'meto_cod');
    }

    public function servicioEntrega()
    {
        return $this->belongsTo(ServicioEntrega::class, 'serv_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedi_id');
    }
}