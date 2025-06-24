<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    
    protected $table = 'categorias';
    protected $primaryKey = 'cate_id';
    
    protected $fillable = ['cate_detalle'];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'cate_id');
    }
}