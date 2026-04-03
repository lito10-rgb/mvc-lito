@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mi Perfil</h2>
    <form action="{{ route('perfil.update') }}" method="POST">
        @csrf
        @method('POST')

        <div class="card p-4">
            <h4 class="mb-3">Datos Generales</h4>
            
            <div class="row g-3">

                <div class="col-md-6">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" value="{{ $profile->email ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Empresa</label>
                    <input type="text" name="empresa" class="form-control" value="{{ $profile->empresa ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Tipo de Documento</label>
                    <input type="text" name="tipo_documento" class="form-control" value="{{ $profile->tipo_documento ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Número de Documento</label>
                    <input type="text" name="num_documento" class="form-control" value="{{ $profile->num_documento ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Teléfono Fijo</label>
                    <input type="text" name="telefono" class="form-control" value="{{ $profile->telefono ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Celular</label>
                    <input type="text" name="celular" class="form-control" value="{{ $profile->celular ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Celular 2</label>
                    <input type="text" name="celular2" class="form-control" value="{{ $profile->celular2 ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Celular 3</label>
                    <input type="text" name="celular3" class="form-control" value="{{ $profile->celular3 ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Celular 4</label>
                    <input type="text" name="celular4" class="form-control" value="{{ $profile->celular4 ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ $profile->whatsapp ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Skype</label>
                    <input type="text" name="skype" class="form-control" value="{{ $profile->skype ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>WeChat</label>
                    <input type="text" name="wechat" class="form-control" value="{{ $profile->wechat ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fechanacimiento" class="form-control" value="{{ $profile->fechanacimiento ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>País</label>
                    <input type="text" name="pais" class="form-control" value="{{ $profile->pais ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Estado / Departamento</label>
                    <input type="text" name="estado" class="form-control" value="{{ $profile->estado ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Provincia</label>
                    <input type="text" name="provincia" class="form-control" value="{{ $profile->provincia ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Distrito</label>
                    <input type="text" name="distrito" class="form-control" value="{{ $profile->distrito ?? '' }}">
                </div>

                <div class="col-md-12">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ $profile->direccion ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Código Postal</label>
                    <input type="text" name="codigopostal" class="form-control" value="{{ $profile->codigopostal ?? '' }}">
                </div>

                <div class="col-md-12">
                    <label>Detalle</label>
                    <textarea name="detalle" class="form-control" rows="3">{{ $profile->detalle ?? '' }}</textarea>
                </div>

                <div class="col-md-6">
                    <label>Email 2</label>
                    <input type="text" name="email2" class="form-control" value="{{ $profile->email2 ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Email 3</label>
                    <input type="text" name="email3" class="form-control" value="{{ $profile->email3 ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Email 4</label>
                    <input type="text" name="email4" class="form-control" value="{{ $profile->email4 ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Web</label>
                    <input type="text" name="web" class="form-control" value="{{ $profile->web ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Web 2</label>
                    <input type="text" name="web2" class="form-control" value="{{ $profile->web2 ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Facebook</label>
                    <input type="text" name="facebook" class="form-control" value="{{ $profile->facebook ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Instagram</label>
                    <input type="text" name="instagram" class="form-control" value="{{ $profile->instagram ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Twitter</label>
                    <input type="text" name="twitter" class="form-control" value="{{ $profile->twitter ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Pinterest</label>
                    <input type="text" name="pinterest" class="form-control" value="{{ $profile->pinterest ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Alibaba</label>
                    <input type="text" name="alibaba" class="form-control" value="{{ $profile->alibaba ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Made in China</label>
                    <input type="text" name="madeinchina" class="form-control" value="{{ $profile->madeinchina ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Cargo</label>
                    <input type="text" name="cargo" class="form-control" value="{{ $profile->cargo ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Categoría</label>
                    <input type="text" name="categoria" class="form-control" value="{{ $profile->categoria ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Subcategoría</label>
                    <input type="text" name="subcategoria" class="form-control" value="{{ $profile->subcategoria ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Tipo Usuario (vendedor/productor)</label>
                    <input type="text" name="tipo_usuario_vendedor_productor" class="form-control" value="{{ $profile->tipo_usuario_vendedor_productor ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{ $profile->codigo ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Cuenta Banco</label>
                    <input type="text" name="cuenta_banco" class="form-control" value="{{ $profile->cuenta_banco ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Representante Legal</label>
                    <input type="text" name="representantelegal" class="form-control" value="{{ $profile->representantelegal ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Fecha Registro</label>
                    <input type="date" name="fecha_registro" class="form-control" value="{{ $profile->fecha_registro ?? '' }}">
                </div>

            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Guardar</button>
            </div>

        </div>
    </form>
</div>
@endsection
