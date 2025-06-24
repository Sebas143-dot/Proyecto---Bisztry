<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('cliente', 'estado')->latest()->paginate(15);
        return view('pedidos.index', compact('pedidos'));
    }

    // Los métodos create, store, edit, etc. para Pedidos pueden ser muy complejos.
    // Por ahora, nos centraremos en mostrar la lista.
    // La creación de un pedido usualmente involucra un carrito de compras, selección de productos, etc.
}