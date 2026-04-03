<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;

class UbicacionController extends Controller
{
    // PAÍS → DepartamentoS (DEPARTAMENTOS)
    public function estados($pais_id)
    {
        return Departamento::where('pais_id', $pais_id)->orderBy('nombre')->get();
    }

    // Departamento → PROVINCIAS
    public function provincias($departamento_id)
    {
        return Provincia::where('departamento_id', $departamento_id)->orderBy('nombre')->get();
    }

    // PROVINCIA → DISTRITOS
    public function distritos($provincia_id)
    {
        return Distrito::where('provincia_id', $provincia_id)->orderBy('nombre')->get();
    }
}
