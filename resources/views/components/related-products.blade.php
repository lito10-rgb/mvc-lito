<div class="relacionados mt-5">
    <h3 class="mb-4">Productos relacionados</h3>

    <div id="relacionadosCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($productos->chunk(4) as $chunkIndex => $chunk)
                <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                    <div class="row g-3">
                        @foreach($chunk as $producto)
                            <div class="col-md-3">
                                <x-product-card-simple :producto="$producto" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Controles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#relacionadosCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#relacionadosCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>
