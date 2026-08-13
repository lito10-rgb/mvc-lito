# Script para descargar imágenes de productos de envapack-peru.com

# URLs de los productos
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
    Write-Host "Directorio creado: $directorioDestino"
}

foreach ($producto in $productos.Keys) {
    $url = $productos[$producto]
    Write-Host "Procesando: $producto"
    Write-Host "URL: $url"
    
    try {
        # Descargar el HTML
        $response = Invoke-WebRequest -Uri $url -UseBasicParsing -ErrorAction Stop
        
        # Buscar URLs de imágenes en el HTML
        $imgTags = $response.ParsedHtml.getElementsByTagName('img')
        
        $imagenesEncontradas = @()
        foreach ($img in $imgTags) {
            $src = $img.src
            if ($src -and $src -notlike "*youtube*" -and $src -notlike "*logo*" -and $src -notlike "*icon*") {
                # Convertir URL relativa a absoluta
                if ($src -notlike "http*") {
                    $src = "https://envapack-peru.com" + $src
                }
                $imagenesEncontradas += $src
            }
        }
        
        if ($imagenesEncontradas.Count -gt 0) {
            # Tomar la primera imagen relevante
            $imagenUrl = $imagenesEncontradas[0]
            Write-Host "  Imagen encontrada: $imagenUrl"
            
            # Generar nombre de archivo seguro
            $nombreArchivo = $producto -replace '[\\/:*?"<>|]', '_' -replace '\s+', '_'
            $extension = [System.IO.Path]::GetExtension($imagenUrl)
            if (-not $extension) {
                $extension = ".jpg"
            }
            $rutaDestino = Join-Path $directorioDestino ($nombreArchivo + $extension)
            
            # Descargar la imagen
            Invoke-WebRequest -Uri $imagenUrl -OutFile $rutaDestino -UseBasicParsing -ErrorAction Stop
            Write-Host "  ✅ Imagen descargada: $rutaDestino"
        } else {
            Write-Host "  ❌ No se encontraron imágenes relevantes"
        }
        
    } catch {
        Write-Host "  ❌ Error: $($_.Exception.Message)"
    }
    
    Write-Host ""
}

Write-Host "Descarga completada. Archivos guardados en: $directorioDestino"