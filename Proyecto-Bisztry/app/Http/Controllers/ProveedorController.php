<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    /**
     * Muestra una lista de todos los proveedores, con opción de búsqueda.
     */
    public function index(Request $request)
    {
        $query = Proveedor::query();
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('prov_nombre', 'like', "%{$search}%")
                  ->orWhere('prov_ruc', 'like', "%{$search}%")
                  ->orWhere('prov_contacto', 'like', "%{$search}%");
            });
        }
        $proveedores = $query->latest()->paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Muestra el formulario para crear un nuevo proveedor.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Guarda un nuevo proveedor en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'prov_ruc' => 'required|string|max:14|unique:proveedores,prov_ruc',
            'prov_nombre' => 'required|string|max:50',
            'prov_contacto' => 'nullable|string|max:50',
            'prov_telefono' => 'nullable|string|max:10',
            'prov_email' => 'nullable|email|max:50|unique:proveedores,prov_email',
        ]);
        Proveedor::create($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Muestra los detalles de un proveedor específico.
     */
    public function show($proveedor_ruc)
    {
        $proveedor = Proveedor::where('prov_ruc', $proveedor_ruc)->firstOrFail();
        $proveedor->load('compras');
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Muestra el formulario para editar un proveedor existente.
     * ======================= SOLUCIÓN ROBUSTA APLICADA AQUÍ =======================
     */
    public function edit($proveedor_ruc)
    {
        $proveedor = Proveedor::where('prov_ruc', $proveedor_ruc)->firstOrFail();
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Actualiza un proveedor específico en la base de datos.
     * ======================= SOLUCIÓN ROBUSTA APLICADA AQUÍ =======================
     */
    public function update(Request $request, $proveedor_ruc)
    {
        $proveedor = Proveedor::where('prov_ruc', $proveedor_ruc)->firstOrFail();

        $request->validate([
            'prov_nombre' => 'required|string|max:50',
            'prov_contacto' => 'nullable|string|max:50',
            'prov_telefono' => 'nullable|string|max:10',
            'prov_email' => [
                'nullable','email','max:50',
                Rule::unique('proveedores')->ignore($proveedor->prov_ruc, 'prov_ruc'),
            ],
        ]);
        $proveedor->update($request->except('prov_ruc'));
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Elimina un proveedor de la base de datos.
     * ======================= SOLUCIÓN ROBUSTA APLICADA AQUÍ =======================
     */
    public function destroy($proveedor_ruc)
    {
        $proveedor = Proveedor::where('prov_ruc', $proveedor_ruc)->firstOrFail();

        if ($proveedor->compras()->exists()) {
            return redirect()->route('proveedores.index')->with('error', 'No se puede eliminar el proveedor porque tiene compras registradas.');
        }
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
