<div class="card mb-3">
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Rubro</label>
            <select name="rubro_id" class="form-control" required>
                <option value="">-- seleccionar --</option>
                @foreach($rubros as $rubro)
                    <option value="{{ $rubro->id }}">
                        {{ $rubro->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Rol</label>
            <select name="rol_id" class="form-control" required>
                <option value="">-- seleccionar --</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}">
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100">
                Asignar seleccionados
            </button>
        </div>

    </div>
</div>
