<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->paginate(10);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('cate_detalle')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate(['prod_nombre' => 'required|string|max:255', 'cate_id' => 'required|exists:categorias,cate_id']);
        Producto::create($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto creado.');
    }
    public function show(Producto $producto)
    {
        // Cargamos las relaciones para poder usarlas en la vista de detalles.
        // Esto es muy eficiente porque pre-carga toda la información necesaria en una sola consulta.
        // Le decimos: "Trae este producto, su categoría, y todas sus variantes. De cada variante, trae también su talla y su color."
        $producto->load('categoria', 'variantes.talla', 'variantes.color');
        
        // Devolvemos la vista 'productos.show' y le pasamos el producto con toda su información.
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('cate_detalle')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate(['prod_nombre' => 'required|string|max:255', 'cate_id' => 'required|exists:categorias,cate_id']);
        $producto->update($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        // Considerar la lógica de variantes antes de eliminar
        if ($producto->variantes()->exists()) {
            return redirect()->route('productos.index')->with('error', 'No se puede eliminar un producto que tiene variantes.');
        }
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}