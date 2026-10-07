<?php

return [
    /*
    | ------------------------------------------------------------------
    | Envío masivo de correos — límites anti-spam
    | ------------------------------------------------------------------
    | lote          : correos por bloque antes de pausar.
    | pausa         : segundos de espera entre bloques.
    | max_total     : tope total por ejecución (0 = sin tope).
    */
    'lote' => (int) env('CORREO_LOTE', 25),

    'pausa' => (int) env('CORREO_PAUSA', 90),

    'max_total' => (int) env('CORREO_MAX_TOTAL', 0),
];