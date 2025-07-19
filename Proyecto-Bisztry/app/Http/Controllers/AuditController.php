<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importa el Facade DB

class AuditController extends Controller
{
    /**
     * Muestra una lista de registros de auditoría.
     */
    public function index()
    {
        // Recuperar los registros de auditoría de la base de datos
        // Ordenamos por la fecha de creación de forma descendente y paginamos los resultados
        $audits = DB::table('audits')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20); // Muestra 20 registros por página

        // Pasar los registros de auditoría a una vista
        return view('audits.index', compact('audits'));
    }
}