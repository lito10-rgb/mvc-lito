@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Filtros laterales -->
        <aside class="col-md-3">
            <h5>Filtrar por:</h5>
            <form>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" class="form-select">
                        <option>Empacadoras</option>
                        <option>Tostadoras</option>
                        <option>Filtros</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" id="marca" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio máximo</label>
                    <input type="number" id="precio" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Aplicar filtros</button>
            </form>
        </aside>

        <!-- Listado de productos -->
        <section class="col-md-9">
            <div class="row row-cols-1 row-cols-md-4 g-4">
                @for($i = 0; $i < 8; $i++)
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ asset('images/producto.jpg') }}" class="card-img-top" alt="Producto">
                        <div class="card-body">
                            <h5 class="card-title">Producto {{ $i+1 }}</h5>
                            <p class="card-text">Descripción breve del producto.</p>
                            <a href="#" class="btn btn-primary">Agregar al carrito</a>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <!-- Paginación -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center
::contentReference[oaicite:0]{index=0}
 
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Filtros laterales -->
        <aside class="col-md-3">
            <h5>Filtrar por:</h5>
            <form>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" class="form-select">
                        <option>Empacadoras</option>
                        <option>Tostadoras</option>
                        <option>Filtros</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" id="marca" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio máximo</label>
                    <input type="number" id="precio" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Aplicar filtros</button>
            </form>
        </aside>

        <!-- Listado de productos -->
        <section class="col-md-9">
            <div class="row row-cols-1 row-cols-md-4 g-4">
                @for($i = 0; $i < 8; $i++)
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ asset('images/producto.jpg') }}" class="card-img-top" alt="Producto">
                        <div class="card-body">
                            <h5 class="card-title">Producto {{ $i+1 }}</h5>
                            <p class="card-text">Descripción breve del producto.</p>
                            <a href="#" class="btn btn-primary">Agregar al carrito</a>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <!-- Paginación -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center
::contentReference[oaicite:0]{index=0}
 
