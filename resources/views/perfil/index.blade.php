@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Mi Perfil</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('perfil.update') }}" method="POST">
        @csrf

        <div class="row">
            <!-- COLUMNA IZQUIERDA -->
            <div class="col-md-6">
                <h5 class="mb-3">Datos Generales</h5>

                <div class="mb-3">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label>Nueva contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Empresa</label>
                    <input type="text" name="empresa" class="form-control"
                           value="{{ $perfil->empresa }}">
                </div>

                <div class="mb-3">
                    <label>Tipo de Documento</label>
                    <select name="tipo_documento" class="form-control">
                        <option value="">Seleccionar</option>
                        <option value="DNI" {{ $perfil->tipo_documento=='DNI'?'selected':'' }}>DNI</option>
                        <option value="RUC" {{ $perfil->tipo_documento=='RUC'?'selected':'' }}>RUC</option>
                        <option value="CE" {{ $perfil->tipo_documento=='CE'?'selected':'' }}>CE</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>N° Documento</label>
                    <input type="text" name="num_documento" class="form-control"
                           value="{{ $perfil->num_documento }}">
                </div>

                <div class="mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ $perfil->telefono }}">
                </div>

                <div class="mb-3">
                    <label>Celular</label>
                    <input type="text" name="celular" class="form-control"
                           value="{{ $perfil->celular }}">
                </div>

                <div class="mb-3">
                    <label>WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control"
                           value="{{ $perfil->whatsapp }}">
                </div>

                <div class="mb-3">
                    <label>Skype</label>
                    <input type="text" name="skype" class="form-control"
                           value="{{ $perfil->skype }}">
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="col-md-6">
                <h5 class="mb-3">Ubicación</h5>

                <div class="mb-3">
                    <label>País *</label>
                    <select name="pais" id="pais" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($paises as $p)
                            <option value="{{ $p->id }}" {{ $perfil->pais == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Departamento *</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($departamentos as $d)
                            <option value="{{ $d->id }}" {{ $perfil->estado == $d->id ? 'selected' : '' }}>
                                {{ $d->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Provincia *</label>
                    <select name="provincia" id="provincia" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($provincias as $prov)
                            <option value="{{ $prov->id }}" {{ $perfil->provincia == $prov->id ? 'selected' : '' }}>
                                {{ $prov->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Distrito *</label>
                    <select name="distrito" id="distrito" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($distritos as $dis)
                            <option value="{{ $dis->id }}" {{ $perfil->distrito == $dis->id ? 'selected' : '' }}>
                                {{ $dis->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="{{ $perfil->direccion }}">
                </div>

                <div class="mb-3">
                    <label>Código Postal</label>
                    <input type="text" name="codigopostal" class="form-control"
                           value="{{ $perfil->codigopostal }}">
                </div>

                <div class="mb-3">
                    <label>Detalle</label>
                    <textarea name="detalle" class="form-control" rows="3">{{ $perfil->detalle }}</textarea>
                </div>
            </div>
        </div>

        <hr>

        <h5 class="mb-3">Redes Sociales y Actividad</h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Web</label>
                <input type="text" name="web" class="form-control" value="{{ $perfil->web }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Facebook</label>
                <input type="text" name="facebook" class="form-control" value="{{ $perfil->facebook }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Instagram</label>
                <input type="text" name="instagram" class="form-control" value="{{ $perfil->instagram }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Twitter</label>
                <input type="text" name="twitter" class="form-control" value="{{ $perfil->twitter }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Pinterest</label>
                <input type="text" name="pinterest" class="form-control" value="{{ $perfil->pinterest }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Cargo</label>
                <input type="text" name="cargo" class="form-control" value="{{ $perfil->cargo }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Categoría</label>
                <select name="categoria" id="categoria" class="form-control">
                    <option value="">Seleccione</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ $perfil->categoria == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Subcategoría</label>
                <select name="subcategoria" id="subcategoria" class="form-control">
                    <option value="">Seleccione</option>
                    @foreach($subcategorias as $sub)
                        <option value="{{ $sub->id }}" {{ $perfil->subcategoria == $sub->id ? 'selected' : '' }}>
                            {{ $sub->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3 cart-actions">Guardar Cambios</button>
    </form>

</div>
@endsection

@section('styles')
@vite(['resources/scss/perfil.scss'])
@endsection

@section('scripts')
@vite(['resources/js/perfil.js'])
@endsection
