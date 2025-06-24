<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    
    protected $table = 'colores';
    protected $primaryKey = 'color_id';
    public $timestamps = false;

    protected $fillable = ['col_detalle'];
}