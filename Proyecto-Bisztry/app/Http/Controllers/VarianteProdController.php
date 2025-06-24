<?php

namespace App\Http\Controllers;

use App\Models\VarianteProd;
use App\Models\Producto;
use App\Models\Talla;
use App\Models\Color;
use Illuminate\Http\Request;

class VarianteProdController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva variante para un producto específico.
     */
    public function create(Producto $producto)
    {
        $tallas = Talla::all();
        $colores = Color::all();
        return view('variantes.create', compact('producto', 'tallas', 'colores'));
    }

    /**
     * Guarda la nueva variante en la base de datos.
     */
    public function store(Request $request, Producto $producto)
    {
        $request->validate([
            'talla_cod' => 'required|exists:tallas,talla_cod',
            'color_id' => 'required|exists:colores,color_id',
            'sku' => 'nullable|string|max:25|unique:variantes_prod,sku',
            'var_stok_actual' => 'required|integer|min:0',
            'var_stock_min' => 'required|integer|min:0',
            'var_precio' => 'required|numeric|min:0',
        ]);

        $producto->variantes()->create($request->all());

        return redirect()->route('productos.show', $producto)->with('success', 'Variante creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una variante existente.
     */
    public function edit(VarianteProd $variante)
    {
        $tallas = Talla::all();
        $colores = Color::all();
        // Cargamos la relación 'producto' para poder usarla en la vista
        $variante->load('producto'); 
        return view('variantes.edit', compact('variante', 'tallas', 'colores'));
    }

    /**
     * Actualiza la variante en la base de datos.
     */
    public function update(Request $request, VarianteProd $variante)
    {
        $request->validate([
            'talla_cod' => 'required|exists:tallas,talla_cod',
            'color_id' => 'required|exists:colores,color_id',
            'sku' => 'nullable|string|max:25|unique:variantes_prod,sku,' . $variante->var_id . ',var_id',
            'var_stok_actual' => 'required|integer|min:0',
            'var_stock_min' => 'required|integer|min:0',
            'var_precio' => 'required|numeric|min:0',
        ]);

        $variante->update($request->all());
        
        // Usamos la relación para volver a la página del producto padre
        return redirect()->route('productos.show', $variante->producto)->with('success', 'Variante actualizada exitosamente.');
    }

    /**
     * Elimina una variante de la base de datos.
     */
    public function destroy(VarianteProd $variante)
    {
        $producto = $variante->producto; // Guardamos el producto padre antes de borrar
        $variante->delete();
        return redirect()->route('productos.show', $producto)->with('success', 'Variante eliminada exitosamente.');
    }
}