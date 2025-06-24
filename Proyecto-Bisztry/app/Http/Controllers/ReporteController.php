<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        // La lógica de reportes se añadirá en el futuro.
        // Por ahora, solo mostramos una vista estática.
        return view('reportes.index');
    }
}