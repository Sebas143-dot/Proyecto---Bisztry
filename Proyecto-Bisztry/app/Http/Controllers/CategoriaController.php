<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')->paginate(10);
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate(['cate_detalle' => 'required|string|max:25|unique:categorias,cate_detalle']);
        Categoria::create($request->all());
        return back()->with('success', 'Categoría creada.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate(['cate_detalle' => 'required|string|max:25|unique:categorias,cate_detalle,'.$categoria->cate_id.',cate_id']);
        $categoria->update($request->all());
        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return back()->with('error', 'No se puede eliminar una categoría que está en uso.');
        }
        $categoria->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}