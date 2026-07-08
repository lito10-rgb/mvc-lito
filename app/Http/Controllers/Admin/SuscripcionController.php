<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index()
    {
        $suscripciones = Suscripcion::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.suscripciones.index', compact('suscripciones'));
    }

    public function destroy($id)
    {
        Suscripcion::findOrFail($id)->delete();
        return redirect()->route('admin.suscripciones.index')->with('success', 'Suscripción eliminada.');
    }
}
