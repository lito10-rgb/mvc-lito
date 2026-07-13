<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class CopiarImagenesCafe extends Command
{
    protected $signature = 'cafe:copiar-imagenes
                            {--solo-portadas : Only copy cover images}
                            {--solo-multimedia : Only copy multimedia images}
                            {--dry-run : Show URLs without downloading}';
    protected $description = 'Download cafe-peruano.com product images from live site and update paths';

    const BASE_URL = 'https://cafe-peruano.com/sistema/admin/';
    const PORTADA_DIR = 'productos/portadas';
    const MULTIMEDIA_DIR = 'productos/multimedia';

    public function handle()
    {
        $onlyPortadas = $this->option('solo-portadas');
        $onlyMultimedia = $this->option('solo-multimedia');
        $dryRun = $this->option('dry-run');

        if (!$onlyMultimedia) {
            $this->copiarPortadas($dryRun);
        }
        if (!$onlyPortadas) {
            $this->copiarMultimedia($dryRun);
        }

        $this->info('Done!');
    }

    protected function download($url)
    {
        $parts = parse_url($url);
        $segments = explode('/', $parts['path'] ?? '');
        $encoded = array_map(function ($s) {
            return rawurlencode(urldecode($s));
        }, $segments);
        $encodedUrl = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '') . implode('/', $encoded);
        if (isset($parts['query'])) {
            $encodedUrl .= '?' . $parts['query'];
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $encodedUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $data === false) {
            return false;
        }
        return $data;
    }

    protected function ensureDir($path)
    {
        $dir = dirname($path);
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
    }

    protected function copiarPortadas(bool $dryRun = false)
    {
        $productos = Producto::where('portada', 'like', 'vistas/img/productos%')->get();
        $bar = $this->output->createProgressBar($productos->count());
        $bar->start();
        $ok = 0;
        $fail = 0;

        foreach ($productos as $p) {
            $filename = basename($p->portada);
            $url = self::BASE_URL . $p->portada;
            $destino = self::PORTADA_DIR . '/' . $filename;

            if ($dryRun) {
                $bar->advance();
                continue;
            }

            if (Storage::disk('public')->exists($destino)) {
                $bar->advance();
                continue;
            }

            $this->ensureDir($destino);
            $img = $this->download($url);
            if ($img === false) {
                $fail++;
                $bar->advance();
                continue;
            }
            Storage::disk('public')->put($destino, $img);
            $p->portada = $destino;
            $p->save();
            $ok++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Portadas: $ok copiadas, $fail fallidas");
    }

    protected function copiarMultimedia(bool $dryRun = false)
    {
        $productos = Producto::where('portada', 'like', 'productos/portadas%')
            ->where(function ($q) {
                $q->where('multimedia', 'like', '%vistas\\\\/img\\\\/multimedia%')
                  ->orWhere('multimedia', 'like', '%vistas/img/multimedia%');
            })->get();
        $bar = $this->output->createProgressBar($productos->count());
        $bar->start();
        $ok = 0;
        $fail = 0;

        foreach ($productos as $p) {
            $multimedia = json_decode($p->multimedia, true);
            if (!is_array($multimedia)) {
                $bar->advance();
                continue;
            }

            $nuevo = [];
            foreach ($multimedia as $item) {
                $foto = is_array($item) ? ($item['foto'] ?? '') : $item;
                if (!$foto || !str_starts_with($foto, 'vistas/img/multimedia')) {
                    $nuevo[] = $foto;
                    continue;
                }

                $filename = basename($foto);
                $subdir = dirname($foto);
                $subdir = str_replace('vistas/img/multimedia/', '', $subdir);

                $url = self::BASE_URL . $foto;
                $destino = self::MULTIMEDIA_DIR . '/' . $subdir . '/' . $filename;

                if ($dryRun) {
                    $nuevo[] = $destino;
                    continue;
                }

                if (Storage::disk('public')->exists($destino)) {
                    $nuevo[] = $destino;
                    continue;
                }

                $this->ensureDir($destino);
                $img = $this->download($url);
                if ($img === false) {
                    $fail++;
                    $nuevo[] = $foto;
                    continue;
                }
                Storage::disk('public')->put($destino, $img);
                $nuevo[] = $destino;
                $ok++;
            }

            if (!$dryRun) {
                $p->multimedia = json_encode($nuevo, JSON_UNESCAPED_SLASHES);
                $p->save();
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Multimedia: $ok copiadas, $fail fallidas");
    }
}
