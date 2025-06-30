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

    public function create(Request $request)
    {
        $ciudades = Ciudad::orderBy('ciud_nombre')->get();
        // Obtenemos la URL de redirección desde la petición, si existe
        $redirect_to = $request->query('redirect_to');
        return view('clientes.create', compact('ciudades', 'redirect_to'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'clie_nombre' => 'required|string|max:50',
            'clie_apellido' => 'required|string|max:50',
            'clie_email' => 'required|email|max:50|unique:clientes,clie_email',
            'clie_telefono' => 'nullable|string|max:20',
            'ciud_cod' => 'required|string|exists:ciudades,ciud_cod',
            'clie_direccion' => 'nullable|string',
            'clie_fecha_nac' => 'nullable|date',
        ]);

        $cliente = Cliente::create($validatedData);

        // Si el formulario nos envió una URL de redirección, la usamos.
        if ($request->filled('_redirect_to')) {
            // Añadimos el ID del nuevo cliente a la URL para poder pre-seleccionarlo.
            $redirectUrl = $request->_redirect_to . '?new_client_id=' . $cliente->clie_id;
            return redirect($redirectUrl)->with('success', 'Cliente creado. Ahora puedes seleccionarlo en la lista.');
        }

        // Si no, volvemos a la lista de clientes (comportamiento por defecto).
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
