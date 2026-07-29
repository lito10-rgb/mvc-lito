@extends('layouts.volt')

@section('title', 'Configurar ' . $negocio->nombre)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Configurar: {{ $negocio->nombre }}</h3>
        <a href="{{ route('admin.negocios.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card border-0 shadow">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="negocioTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                        <i class="fas fa-info-circle me-1"></i> General
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="colores-tab" data-bs-toggle="tab" data-bs-target="#colores" type="button" role="tab">
                        <i class="fas fa-palette me-1"></i> Colores
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="banners-tab" data-bs-toggle="tab" data-bs-target="#banners" type="button" role="tab">
                        <i class="fas fa-images me-1"></i> Banners
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab">
                        <i class="fas fa-shoe-prints me-1"></i> Footer
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ubicacion-tab" data-bs-toggle="tab" data-bs-target="#ubicacion" type="button" role="tab">
                        <i class="fas fa-map-marker-alt me-1"></i> Ubicación
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.negocios.update', $negocio) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="tab-content" id="negocioTabsContent">
                    {{-- TAB GENERAL --}}
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del negocio</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $negocio->nombre) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="empresa" class="form-label">Datos de la Empresa</label>
                            <textarea name="empresa" id="empresa" class="form-control" rows="4" placeholder="Información de la empresa para catálogos...">{{ old('empresa', $negocio->empresa) }}</textarea>
                            <small class="text-muted">Se muestra en los catálogos generados como encabezado y pie de página.</small>
                        </div>
                        <div class="mb-3">
                            <label for="dominio" class="form-label">Dominio</label>
                            <input type="text" name="dominio" id="dominio" class="form-control" value="{{ old('dominio', $negocio->dominio) }}" required>
                            <small class="text-muted">Ej: equiposymaquinas.com</small>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            @if($negocio->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $negocio->logo) }}" style="height:60px;" alt="Logo actual">
                                </div>
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                            <small class="text-muted">Dejar vacío para mantener el actual.</small>
                        </div>
                        <div class="mb-3">
                            <label for="logo_height" class="form-label">Altura del logo (px)</label>
                            <input type="number" name="logo_height" id="logo_height" class="form-control" value="{{ old('logo_height', $negocio->logo_height ?? '70') }}" min="20" max="300">
                            <small class="text-muted">En píxeles. Default: 70px</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estilo de los enlaces del menú</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nav_tipo" id="nav_tipo_boton" value="boton" {{ old('nav_tipo', $negocio->nav_tipo ?? 'boton') == 'boton' ? 'checked' : '' }}>
                                <label class="form-check-label" for="nav_tipo_boton">Botones (con fondo de color)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nav_tipo" id="nav_tipo_texto" value="texto" {{ old('nav_tipo', $negocio->nav_tipo ?? 'boton') == 'texto' ? 'checked' : '' }}>
                                <label class="form-check-label" for="nav_tipo_texto">Solo texto (links normales)</label>
                            </div>
                        </div>
                    </div>

                    {{-- TAB COLORES --}}
                    <div class="tab-pane fade" id="colores" role="tabpanel">
                        <p class="text-muted">Colores principales del sitio (formato hexadecimal, ej: #103067).</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="color_primary" class="form-label">Color Primario</label>
                                <div class="input-group">
                                    <input type="color" name="color_primary_picker" id="color_primary_picker" class="form-control form-control-color" value="{{ old('color_primary', $negocio->color_primary ?? '#103067') }}" oninput="document.getElementById('color_primary').value=this.value">
                                    <input type="text" name="color_primary" id="color_primary" class="form-control" value="{{ old('color_primary', $negocio->color_primary ?? '') }}" placeholder="#103067" oninput="document.getElementById('color_primary_picker').value=this.value">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_secondary" class="form-label">Color Secundario</label>
                                <div class="input-group">
                                    <input type="color" id="color_secondary_picker" class="form-control form-control-color" value="{{ old('color_secondary', $negocio->color_secondary ?? '#c5a200') }}" oninput="document.getElementById('color_secondary').value=this.value">
                                    <input type="text" name="color_secondary" id="color_secondary" class="form-control" value="{{ old('color_secondary', $negocio->color_secondary ?? '') }}" placeholder="#c5a200" oninput="document.getElementById('color_secondary_picker').value=this.value">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_accent" class="form-label">Color Acento</label>
                                <div class="input-group">
                                    <input type="color" id="color_accent_picker" class="form-control form-control-color" value="{{ old('color_accent', $negocio->color_accent ?? '#ffc107') }}" oninput="document.getElementById('color_accent').value=this.value">
                                    <input type="text" name="color_accent" id="color_accent" class="form-control" value="{{ old('color_accent', $negocio->color_accent ?? '') }}" placeholder="#ffc107" oninput="document.getElementById('color_accent_picker').value=this.value">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_nav_btn_texto" class="form-label">Texto botones nav</label>
                                <div class="input-group">
                                    <input type="color" id="color_nav_btn_texto_picker" class="form-control form-control-color" value="{{ old('color_nav_btn_texto', $negocio->color_nav_btn_texto ?? '#000000') }}" oninput="document.getElementById('color_nav_btn_texto').value=this.value">
                                    <input type="text" name="color_nav_btn_texto" id="color_nav_btn_texto" class="form-control" value="{{ old('color_nav_btn_texto', $negocio->color_nav_btn_texto ?? '') }}" placeholder="#000000" oninput="document.getElementById('color_nav_btn_texto_picker').value=this.value">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_nav_texto" class="form-label">Color texto nav (modo texto)</label>
                                <div class="input-group">
                                    <input type="color" id="color_nav_texto_picker" class="form-control form-control-color" value="{{ old('color_nav_texto', $negocio->color_nav_texto ?? '#D4A373') }}" oninput="document.getElementById('color_nav_texto').value=this.value">
                                    <input type="text" name="color_nav_texto" id="color_nav_texto" class="form-control" value="{{ old('color_nav_texto', $negocio->color_nav_texto ?? '') }}" placeholder="#D4A373" oninput="document.getElementById('color_nav_texto_picker').value=this.value">
                                </div>
                                <small class="text-muted">Se usa cuando el menú está en modo "Solo texto"</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_header_bg" class="form-label">Fondo del Header</label>
                                <div class="input-group">
                                    <input type="color" id="color_header_bg_picker" class="form-control form-control-color" value="{{ old('color_header_bg', $negocio->color_header_bg ?? '#1a1a1a') }}" oninput="document.getElementById('color_header_bg').value=this.value">
                                    <input type="text" name="color_header_bg" id="color_header_bg" class="form-control" value="{{ old('color_header_bg', $negocio->color_header_bg ?? '') }}" placeholder="#1a1a1a" oninput="document.getElementById('color_header_bg_picker').value=this.value">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_footer_bg" class="form-label">Fondo del Footer</label>
                                <div class="input-group">
                                    <input type="color" id="color_footer_bg_picker" class="form-control form-control-color" value="{{ old('color_footer_bg', $negocio->color_footer_bg ?? '#1a1a1a') }}" oninput="document.getElementById('color_footer_bg').value=this.value">
                                    <input type="text" name="color_footer_bg" id="color_footer_bg" class="form-control" value="{{ old('color_footer_bg', $negocio->color_footer_bg ?? '') }}" placeholder="#1a1a1a" oninput="document.getElementById('color_footer_bg_picker').value=this.value">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_nav_btn" class="form-label">Fondo botones nav</label>
                                <div class="input-group">
                                    <input type="color" id="color_nav_btn_picker" class="form-control form-control-color" value="{{ old('color_nav_btn', $negocio->color_nav_btn ?? '#D4A373') }}" oninput="document.getElementById('color_nav_btn').value=this.value">
                                    <input type="text" name="color_nav_btn" id="color_nav_btn" class="form-control" value="{{ old('color_nav_btn', $negocio->color_nav_btn ?? '') }}" placeholder="#D4A373" oninput="document.getElementById('color_nav_btn_picker').value=this.value">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color_nav_btn_texto" class="form-label">Texto botones nav</label>
                                <div class="input-group">
                                    <input type="color" id="color_nav_btn_texto_picker" class="form-control form-control-color" value="{{ old('color_nav_btn_texto', $negocio->color_nav_btn_texto ?? '#000000') }}" oninput="document.getElementById('color_nav_btn_texto').value=this.value">
                                    <input type="text" name="color_nav_btn_texto" id="color_nav_btn_texto" class="form-control" value="{{ old('color_nav_btn_texto', $negocio->color_nav_btn_texto ?? '') }}" placeholder="#000000" oninput="document.getElementById('color_nav_btn_texto_picker').value=this.value">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB BANNERS --}}
                    <div class="tab-pane fade" id="banners" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-images me-2"></i>
                            <strong>Slides del Home:</strong> Administra las imágenes del carrusel de inicio (varias imágenes con texto y botón cada una).
                            <a href="{{ route('admin.negocios.slides.index', $negocio) }}" class="btn btn-sm btn-warning ms-3">
                                <i class="fas fa-edit"></i> Gestionar Slides
                            </a>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="banner_categoria" class="form-label">Banner de categoría</label>
                            @if($negocio->banner_categoria)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $negocio->banner_categoria) }}" class="img-fluid rounded" style="max-height:150px;" alt="Banner actual">
                                </div>
                            @endif
                            <input type="file" name="banner_categoria" id="banner_categoria" class="form-control" accept="image/*">
                            <small class="text-muted">Se muestra en las páginas de categoría. Dejar vacío para mantener el actual.</small>
                        </div>
                        <div class="mb-3">
                            <label for="banner_subcategoria" class="form-label">Banner de subcategoría</label>
                            @if($negocio->banner_subcategoria)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $negocio->banner_subcategoria) }}" class="img-fluid rounded" style="max-height:150px;" alt="Banner actual">
                                </div>
                            @endif
                            <input type="file" name="banner_subcategoria" id="banner_subcategoria" class="form-control" accept="image/*">
                            <small class="text-muted">Se muestra en las páginas de subcategoría.</small>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_banner_subcategoria" id="remove_banner_subcategoria" value="1">
                                <label class="form-check-label text-danger" for="remove_banner_subcategoria">
                                    Eliminar banner de subcategoría (sin banner)
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- TAB FOOTER --}}
                    <div class="tab-pane fade" id="footer" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="footer_phone" class="form-label">Teléfono</label>
                                <input type="text" name="footer_phone" id="footer_phone" class="form-control" value="{{ old('footer_phone', $negocio->footer_phone ?? config('theme.contact.phone')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_email" class="form-label">Email(s) - separados por ;</label>
                                <input type="text" name="footer_email" id="footer_email" class="form-control" value="{{ old('footer_email', $negocio->footer_email ?? config('theme.contact.email')) }}">
                                <small class="text-muted">Varios emails separados por punto y coma (;)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" name="footer_whatsapp" id="footer_whatsapp" class="form-control" value="{{ old('footer_whatsapp', $negocio->footer_whatsapp ?? config('theme.contact.whatsapp')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_address" class="form-label">Dirección</label>
                                <input type="text" name="footer_address" id="footer_address" class="form-control" value="{{ old('footer_address', $negocio->footer_address ?? config('theme.contact.address')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_facebook" class="form-label">Facebook URL</label>
                                <input type="url" name="footer_facebook" id="footer_facebook" class="form-control" value="{{ old('footer_facebook', $negocio->footer_facebook ?? config('theme.social.facebook')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_twitter" class="form-label">Twitter URL</label>
                                <input type="url" name="footer_twitter" id="footer_twitter" class="form-control" value="{{ old('footer_twitter', $negocio->footer_twitter ?? config('theme.social.twitter')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_instagram" class="form-label">Instagram URL</label>
                                <input type="url" name="footer_instagram" id="footer_instagram" class="form-control" value="{{ old('footer_instagram', $negocio->footer_instagram ?? config('theme.social.instagram')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_linkedin" class="form-label">LinkedIn URL</label>
                                <input type="url" name="footer_linkedin" id="footer_linkedin" class="form-control" value="{{ old('footer_linkedin', $negocio->footer_linkedin ?? config('theme.social.linkedin')) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="footer_html" class="form-label">HTML Personalizado (opcional)</label>
                            <textarea name="footer_html" id="footer_html" class="form-control" rows="4">{{ old('footer_html', $negocio->footer_html ?? '') }}</textarea>
                            <small class="text-muted">Si necesitas contenido adicional en el footer (copyright, créditos, etc.). Se insertará después de la información de contacto.</small>
                        </div>
                    </div>

                    {{-- TAB UBICACION --}}
                    <div class="tab-pane fade" id="ubicacion" role="tabpanel">
                        <p class="text-muted mb-3">Haz clic en el mapa para colocar el marcador de tu negocio, o busca una dirección.</p>
                        <div class="row mb-3">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" id="mapSearch" class="form-control" placeholder="Buscar dirección (ej: Av. Principal 123, Lima, Perú)">
                                    <button type="button" class="btn btn-outline-primary" id="mapSearchBtn">Buscar</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary w-100" id="mapClearBtn">
                                    <i class="fas fa-times me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                        <div id="mapAdmin" style="height:400px; border-radius:8px; border:2px solid #dee2e6;"></div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label">Latitud</label>
                                <input type="text" name="map_lat" id="map_lat" class="form-control" value="{{ old('map_lat', $negocio->map_lat ?? '') }}" placeholder="Selecciona en el mapa o escribe">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Longitud</label>
                                <input type="text" name="map_lng" id="map_lng" class="form-control" value="{{ old('map_lng', $negocio->map_lng ?? '') }}" placeholder="Selecciona en el mapa o escribe">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Foto del edificio</label>
                                @if($negocio->map_photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $negocio->map_photo) }}" style="height:60px; border-radius:6px;" alt="Foto del edificio">
                                    </div>
                                @endif
                                <input type="file" name="map_photo" id="map_photo" class="form-control" accept="image/*">
                                <small class="text-muted">Se muestra en el popup del mapa.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('admin.negocios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Guardar configuración</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('map_lat');
    const lngInput = document.getElementById('map_lng');
    const latVal = parseFloat(latInput.value);
    const lngVal = parseFloat(lngInput.value);

    const map = L.map('mapAdmin').setView(
        (latVal && lngVal) ? [latVal, lngVal] : [-12.0464, -77.0428],
        (latVal && lngVal) ? 15 : 6
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19
    }).addTo(map);

    let marker = null;

    if (latVal && lngVal) {
        marker = L.marker([latVal, lngVal]).addTo(map);
    }

    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        latInput.value = lat;
        lngInput.value = lng;
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
    });

    document.getElementById('mapSearchBtn').addEventListener('click', function() {
        const query = document.getElementById('mapSearch').value.trim();
        if (!query) return;
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
            .then(r => r.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat).toFixed(6);
                    const lng = parseFloat(data[0].lon).toFixed(6);
                    map.setView([lat, lng], 16);
                    latInput.value = lat;
                    lngInput.value = lng;
                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lng]).addTo(map);
                } else {
                    alert('Direccion no encontrada. Intenta con otro texto.');
                }
            });
    });

    document.getElementById('mapSearch').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('mapSearchBtn').click();
        }
    });

    document.getElementById('mapClearBtn').addEventListener('click', function() {
        latInput.value = '';
        lngInput.value = '';
        if (marker) { map.removeLayer(marker); marker = null; }
        map.setView([-12.0464, -77.0428], 6);
    });

    document.getElementById('ubicacion-tab').addEventListener('shown.bs.tab', function() {
        map.invalidateSize();
    });

    [latInput, lngInput].forEach(function(input) {
        input.addEventListener('change', function() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (lat && lng) {
                map.setView([lat, lng], 16);
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
            }
        });
    });
});
</script>
@endpush
