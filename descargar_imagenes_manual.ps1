# Script simplificado para descargar imágenes manualmente desde envapack-peru.com

# Lista de productos y sus URLs para descarga manual
$productos = @{
    "Bolsa Fuelle 4 Sellos con Sin Válvula" = "https://envapack-peru.com/producto/bolsas-fuelle-4-sellos-con-o-sin-valvula/"
    "Bolsa Fuelle 8 Sellos con Sin Válvula" = "https://envapack-peru.com/producto/bolsas-fuelle-8-sellos-con-o-sin-valvula/"
    "Bolsa Doypack con Sin Válvula" = "https://envapack-peru.com/producto/bolsas-doypack-con-sin-valvula/"
    "Bolsa Doypack Kraft" = "https://envapack-peru.com/producto/bolsas-doypack-kraft/"
    "Bolsa Doypack Transparente" = "https://envapack-peru.com/producto/bolsas-doypack-transparentes/"
    "Bolsas Planas Flexibles" = "https://envapack-peru.com/producto/bolsas-planas-flexibles/"
    "Bolsas Kraft con Asa" = "https://envapack-peru.com/producto/bolsas-kraft-con-asa/"
    "Bolsas Kraft SOS" = "https://envapack-peru.com/producto/bolsas-kraft-sos/"
}

# Directorio de destino
$directorioDestino = "D:\26\cotizacion\imagenes_ENVAPACK"

# Crear directorio si no existe
if (-not (Test-Path $directorioDestino)) {
    New-Item -ItemType Directory -Path $directorioDestino -Force
}

Write-Host "=== INSTRUCCIONES PARA DESCARGA MANUAL DE IMÁGENES ===" -ForegroundColor Green
Write-Host ""
Write-Host "Abre las siguientes URLs en tu navegador y descarga las imágenes de los productos:" -ForegroundColor Yellow
Write-Host ""

$contador = 1
foreach ($producto in $productos.Keys) {
    $url = $productos[$producto]
    
    # Generar nombre de archivo sugerido
    $nombreArchivo = $producto -replace '[\\/:*?"<>|]', '_' -replace '\s+', '_'
    
    Write-Host "$contador. $producto" -ForegroundColor Cyan
    Write-Host "   URL: $url" -ForegroundColor White
    Write-Host "   Guardar como: $nombreArchivo.jpg" -ForegroundColor Gray
    Write-Host "   Ubicación: $directorioDestino" -ForegroundColor Gray
    Write-Host ""
    
    $contador++
}

Write-Host "=== PASOS A SEGUIR ===" -ForegroundColor Green
Write-Host "1. Abre cada URL en tu navegador" -ForegroundColor Yellow
Write-Host "2. Haz clic derecho en la imagen principal del producto" -ForegroundColor Yellow
Write-Host "3. Selecciona 'Guardar imagen como...'" -ForegroundColor Yellow
Write-Host "4. Guárdala con el nombre sugerido en: $directorioDestino" -ForegroundColor Yellow
Write-Host "5. Una vez descargadas todas las imágenes, ejecuta:" -ForegroundColor Yellow
Write-Host "   php artisan productos:asignar-imagenes 'EMPAQUES ENVAPACK' 'D:\26\cotizacion\imagenes_ENVAPACK'" -ForegroundColor Cyan
Write-Host ""

# Abrir las URLs en el navegador automáticamente
Write-Host "¿Deseas abrir las URLs en tu navegador ahora? (S/N)" -ForegroundColor Yellow
$respuesta = Read-Host

if ($respuesta -eq 'S' -or $respuesta -eq 's') {
    foreach ($url in $productos.Values) {
        Start-Process $url
        Start-Sleep -Seconds 2
    }
}