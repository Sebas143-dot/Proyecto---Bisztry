<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioEntrega extends Model
{
    use HasFactory;
    
    protected $table = 'servicios_entrega';
    protected $primaryKey = 'serv_id';
    
    protected $fillable = ['serv_nombre', 'serv_costo'];
}