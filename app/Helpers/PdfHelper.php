<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class PdfHelper
{
    public static function generateFromHtml(string $html, string $outputPath): bool
    {
        $tmpDir = storage_path('app/temp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $htmlFile = $tmpDir . '/' . uniqid('html_') . '.html';
        $pdfFile = $tmpDir . '/' . uniqid('pdf_') . '.pdf';

        file_put_contents($htmlFile, $html);

        $chromePath = 'C:\Program Files\Google\Chrome\Application\chrome.exe';
        $cmd = sprintf(
            '"%s" --headless --disable-gpu --print-to-pdf-no-header --print-to-pdf="%s" "%s" 2>&1',
            $chromePath,
            $pdfFile,
            $htmlFile
        );

        exec($cmd, $output, $exitCode);

        $success = $exitCode === 0 && file_exists($pdfFile);

        if ($success) {
            $dest = Storage::disk('public')->path($outputPath);
            $destDir = dirname($dest);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            copy($pdfFile, $dest);
        } else {
            $errorMsg = implode("\n", $output);
            \Log::error("PDF generation failed: " . $errorMsg);
        }

        @unlink($htmlFile);
        @unlink($pdfFile);

        return $success;
    }
}
