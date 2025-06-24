<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $table = 'provincias';
    protected $primaryKey = 'prov_cod';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['prov_cod', 'prov_nomnbre'];

    // Relación: Una Provincia tiene muchas Ciudades
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'prov_cod');
    }
}