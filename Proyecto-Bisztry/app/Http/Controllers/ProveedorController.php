<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }
    
public function show(Proveedor $proveedor)
{
    // Carga la relación 'compras' para usarla en la vista de detalles
    $proveedor->load('compras');
    return view('proveedores.show', compact('proveedor'));
}
}