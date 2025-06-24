<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;
    
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