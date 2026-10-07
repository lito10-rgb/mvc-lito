<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::with('user')
            ->orderByDesc('enviado_en');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('negocio')) {
            $query->where('negocio', $request->negocio);
        }
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('enviado_en', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('enviado_en', '<=', $request->fecha_hasta);
        }

        $logs = $query->paginate(50)->withQueryString();

        $tipos = EmailLog::select('tipo')->distinct()->pluck('tipo');
        $estados = ['enviado', 'fallido'];
        $negocios = EmailLog::whereNotNull('negocio')->distinct()->pluck('negocio');
        $batchIds = EmailLog::select('batch_id')->distinct()->orderByDesc('batch_id')->limit(20)->pluck('batch_id');

        // Campañas (listas guardadas)
        $campaigns = EmailCampaign::with('creador')
            ->orderByDesc('enviado_en')
            ->paginate(20)->withQueryString();

        return view('admin.email-logs.index', compact(
            'logs',
            'tipos',
            'estados',
            'negocios',
            'batchIds',
            'campaigns'
        ));
    }

    public function export(Request $request)
    {
        $query = EmailLog::with('user')
            ->orderByDesc('enviado_en');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('negocio')) {
            $query->where('negocio', $request->negocio);
        }
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('enviado_en', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('enviado_en', '<=', $request->fecha_hasta);
        }

        $logs = $query->get();

        $filename = 'email_logs_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, [
                'ID',
                'Fecha/Hora',
                'Tipo',
                'Estado',
                'Negocio',
                'Remitente (nombre)',
                'Remitente (email)',
                'Destinatario (ID)',
                'Destinatario (nombre)',
                'Destinatario (email)',
                'Asunto',
                'Batch ID',
                'Error',
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->enviado_en->format('Y-m-d H:i:s'),
                    $log->tipo,
                    $log->estado,
                    $log->negocio ?? '',
                    $log->from_name ?? '',
                    $log->from_email ?? '',
                    $log->user_id ?? '',
                    $log->nombre ?? '',
                    $log->email,
                    $log->asunto,
                    $log->batch_id,
                    $log->error ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCampaigns(Request $request)
    {
        $campaigns = EmailCampaign::with('creador')
            ->orderByDesc('enviado_en')
            ->get();

        $filename = 'email_campaigns_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($campaigns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID',
                'Nombre',
                'Tipo',
                'Negocio',
                'Fecha/Hora',
                'Enviados',
                'Fallidos',
                'Batch ID',
                'Creado por',
                'Notas',
            ]);

            foreach ($campaigns as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->nombre,
                    $c->tipo,
                    $c->negocio ?? '',
                    $c->enviado_en->format('Y-m-d H:i:s'),
                    $c->total_enviados,
                    $c->total_fallidos,
                    $c->batch_id,
                    $c->creador?->name ?? '',
                    $c->notas ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}