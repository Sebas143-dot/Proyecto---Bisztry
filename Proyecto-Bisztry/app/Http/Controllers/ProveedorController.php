<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('prov_nombre', 'like', "%{$search}%")
                  ->orWhere('prov_ruc', 'like', "%{$search}%")
                  ->orWhere('prov_contacto', 'like', "%{$search}%");
        }
        $proveedores = $query->latest()->paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'prov_ruc' => 'required|string|max:14|unique:proveedores,prov_ruc',
            'prov_nombre' => 'required|string|max:50',
            'prov_contacto' => 'nullable|string|max:50',
            'prov_telefono' => 'nullable|string|max:10',
            'prov_email' => 'nullable|email|max:50',
        ]);
        Proveedor::create($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Proveedor $proveedor)
    {
        $proveedor->load('compras');
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'prov_nombre' => 'required|string|max:50',
            'prov_contacto' => 'nullable|string|max:50',
            'prov_telefono' => 'nullable|string|max:10',
            'prov_email' => 'nullable|email|max:50|unique:proveedores,prov_email,' . $proveedor->prov_ruc . ',prov_ruc',
        ]);
        $proveedor->update($request->except('prov_ruc'));
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->compras()->exists()) {
            return redirect()->route('proveedores.index')->with('error', 'No se puede eliminar el proveedor porque tiene compras registradas.');
        }
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}