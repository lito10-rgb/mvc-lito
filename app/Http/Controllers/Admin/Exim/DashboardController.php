<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Cliente;
use App\Models\Exim\Contenedor;
use App\Models\Exim\Cotizacion;
use App\Models\Exim\Documento;
use App\Models\Exim\GastoLogistico;
use App\Models\Exim\GastoOperativo;
use App\Models\Exim\Incoterm;
use App\Models\Exim\Moneda;
use App\Models\Exim\Muestra;
use App\Models\Exim\Pallet;
use App\Models\Exim\Producto;
use App\Models\Exim\Seguro;
use App\Models\Exim\Transporte;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'clientes'          => Cliente::count(),
            'cotizaciones'      => Cotizacion::count(),
            'productos'         => Producto::count(),
            'muestras'          => Muestra::count(),
            'documentos'        => Documento::count(),
            'monedas'           => Moneda::count(),
            'incoterms'         => Incoterm::count(),
            'transportes'       => Transporte::count(),
            'contenedores'      => Contenedor::count(),
            'gastos_logisticos' => GastoLogistico::count(),
            'gastos_operativos' => GastoOperativo::count(),
            'pallets'           => Pallet::count(),
            'seguros'           => Seguro::count(),
        ];

        return view('admin.exim.dashboard', compact('counts'));
    }
}
