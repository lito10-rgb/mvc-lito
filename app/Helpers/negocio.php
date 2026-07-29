<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

if (!function_exists('negocio_actual_id')) {
    function negocio_actual_id(): int
    {
        if (Session::has('negocio_id')) {
            return (int) Session::get('negocio_id');
        }

        if (Request::has('negocio_id')) {
            $id = (int) Request::input('negocio_id');
            Session::put('negocio_id', $id);
            return $id;
        }

        $host = Request::getHost();
        $dominios = config('negocio.dominios', []);
        return $dominios[$host] ?? config('negocio.default', 1);
    }
}

if (!function_exists('negocio_actual_nombre')) {
    function negocio_actual_nombre(): string
    {
        $id = negocio_actual_id();
        return \App\Models\Negocio::find($id)?->nombre ?? 'Equipos y Maquinas';
    }
}

if (!function_exists('negocios_disponibles')) {
    function negocios_disponibles()
    {
        return \App\Models\Negocio::all();
    }
}

if (!function_exists('negocio_actual')) {
    function negocio_actual(): ?\App\Models\Negocio
    {
        return \App\Models\Negocio::find(negocio_actual_id());
    }
}

if (!function_exists('negocio_color')) {
    function negocio_color(string $key, string $default = ''): string
    {
        $negocio = negocio_actual();
        $color = $negocio?->{'color_' . $key} ?? '';
        return $color ?: config('theme.colors.' . $key, $default);
    }
}

if (!function_exists('negocio_theme')) {
    function negocio_theme(string $key, string $default = ''): string
    {
        $negocio = negocio_actual();
        $value = $negocio?->$key ?? '';
        return $value ?: $default;
    }
}
