<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::with('ciudad.provincia');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('clie_nombre', 'like', "%{$search}%")
                  ->orWhere('clie_apellido', 'like', "%{$search}%")
                  ->orWhere('clie_email', 'like', "%{$search}%");
        }

        $clientes = $query->latest()->paginate(10); 
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $ciudades = Ciudad::orderBy('ciud_nombre')->get();
        return view('clientes.create', compact('ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'clie_nombre' => 'required|string|max:50',
            'clie_apellido' => 'required|string|max:50',
            'clie_email' => 'required|email|max:50|unique:clientes,clie_email',
            'clie_telefono' => 'nullable|string|max:20',
            'ciud_cod' => 'required|string|exists:ciudades,ciud_cod',
            'clie_direccion' => 'nullable|string',
            'clie_fecha_nac' => 'nullable|date',
        ]);

        Cliente::create($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }
    
    public function show(Cliente $cliente)
    {
        $cliente->load('ciudad.provincia', 'pedidos');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $ciudades = Ciudad::orderBy('ciud_nombre')->get();
        return view('clientes.edit', compact('cliente', 'ciudades'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'clie_nombre' => 'required|string|max:50',
            'clie_apellido' => 'required|string|max:50',
            'clie_email' => 'required|email|max:50|unique:clientes,clie_email,' . $cliente->clie_id . ',clie_id',
            'clie_telefono' => 'nullable|string|max:20',
            'ciud_cod' => 'required|string|exists:ciudades,ciud_cod',
            'clie_direccion' => 'nullable|string',
            'clie_fecha_nac' => 'nullable|date',
        ]);

        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}