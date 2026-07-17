<section class="bg-light py-4">
    <div class="container">
        <h2 class="mb-4 text-center">Busca tu equipo ideal</h2>

        <!-- Fila 1: Búsqueda general -->
        <form action="{{ route('productos.buscar') }}" method="GET" class="mb-3">
            <div class="row g-3 justify-content-center align-items-center">
                
                <!-- Campo de búsqueda -->
                <div class="col-md-5 col-12">
                    <input type="text" name="q" class="form-control select-dorado"
                           placeholder="Buscar por nombre o descripción"
                           value="{{ request('q') }}">
                </div>

                <!-- Switch / checkbox "Más vistos" -->
                <div class="col-md-2 col-6 d-flex justify-content-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="masVistos" name="mas_vistos"
                               {{ request()->has('mas_vistos') ? 'checked' : '' }}>
                        <label class="form-check-label" for="masVistos">Más vistos</label>
                    </div>
                </div>

                <!-- Botón de búsqueda -->
                <div class="col-md-auto col-6 text-center">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>

            </div>
        </form>


        <!-- Fila 2: Filtros detallados -->
        <form action="{{ route('productos.buscar') }}" method="GET">
            <div class="row g-3 align-items-center justify-content-center">
                <div class="col-md-2 col-6">
                    <select name="categoria" class="form-select select-dorado">
                        <option value="">Categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->nombre }}" {{ request('categoria') == $cat->nombre ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="marca" class="form-select select-dorado">
                        <option value="">Marca</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->nombre }}" {{ request('marca') == $m->nombre ? 'selected' : '' }}>{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <input type="text" name="capacidad" class="form-control select-dorado" placeholder="Capacidad (kg)">
                </div>
                <div class="col-md-2 col-6">
                    <input type="number" name="precio_min" class="form-control select-dorado" placeholder="Precio mínimo">
                </div>
                <div class="col-md-2 col-6">
                    <input type="number" name="precio_max" class="form-control select-dorado" placeholder="Precio máximo">
                </div>
                <div class="col-md-auto col-12 text-center">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
