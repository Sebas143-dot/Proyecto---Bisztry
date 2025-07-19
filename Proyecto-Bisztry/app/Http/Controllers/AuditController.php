<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit; // Asegúrate de que esta es la ruta correcta a tu modelo Audit

class AuditController extends Controller
{
    /**
     * Muestra una lista de registros de auditoría.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Recuperar los registros de auditoría usando el modelo Eloquent 'Audit'.
        // Es crucial usar 'with(['user', 'auditable'])' para cargar ambas relaciones
        // (el usuario que realizó la acción y el modelo que fue auditado)
        // Esto evita múltiples consultas a la base de datos (problema de N+1 queries).
        $audits = Audit::with(['user', 'auditable']) // <-- ¡Aquí está el cambio clave!
                       ->latest() // Ordena por 'created_at' de forma descendente
                       ->paginate(20); // Muestra 20 registros por página

        // Pasa los registros de auditoría paginados a la vista.
        return view('audits.index', compact('audits'));
    }
}