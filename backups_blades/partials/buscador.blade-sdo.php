<section class="bg-light py-4">
    <div class="container">
        <h2 class="mb-4 text-center">Busca tu equipo ideal</h2>
        <form action="{{ route('productos.buscar') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="categoria" class="form-select select-dorado">
                        <option value="">Categoria de equipo</option>
                        <option value="laborum">laborum</option>
                        <option value="empaque">Empacadora</option>
                        <option value="filtro">Filtro</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="marca" class="form-select select-dorado">
                        <option value="">Marca</option>
                        <option value="marca1">Marca 1</option>
                        <option value="marca2">Marca 2</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="capacidad" class="form-control select-dorado" placeholder="Capacidad (kg)">
                </div>
              <!--   <div class="col-md-2">
                    <input type="number" name="precio_min" class="form-control select-dorado placeholder="Precio mínimo">
                </div> -->
                <input type="number" name="precio_min" class="form-control select-dorado" placeholder="Precio mínimo">

                <div class="col-md-2">
                    <input type="number" name="precio_max" class="form-control select-dorado" placeholder="Precio máximo">
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-search me-2"></i>Buscar</button>
                </div>
            </div>
        </form>
    </div>
</section>
