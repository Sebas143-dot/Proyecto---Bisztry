<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // 1. IMPORTANTE: Añadir esta línea
use OwenIt\Auditing\Models\Audit; // Asegúrate de que esta es la ruta correcta a tu modelo Audit

class AuditController extends Controller
{
    /**
     * Muestra una lista de registros de auditoría, aplicando filtros si existen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request) // 2. AÑADIR: Recibir el objeto Request
    {
        // Iniciamos la consulta base con las relaciones necesarias para optimizar.
        // El eager loading con 'user' y 'auditable' es opcional pero muy recomendado.
        $query = Audit::with('user')->latest();

        // 3. LÓGICA DEL FILTRO: Verificamos si la URL contiene un filtro de 'event'.
        // Si existe, añadimos una condición 'where' a nuestra consulta.
        if ($request->has('event') && in_array($request->event, ['created', 'updated', 'deleted', 'restored'])) {
            $query->where('event', $request->event);
        }

        // Finalmente, paginamos el resultado de la consulta (ya sea la original o la filtrada).
        $audits = $query->paginate(20); // Puedes ajustar el número de registros por página

        // Pasamos los registros a la vista.
        return view('audits.index', compact('audits'));
    }
}