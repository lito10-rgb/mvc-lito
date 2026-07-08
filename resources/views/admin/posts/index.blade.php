@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Posts / Contenidos</h3>

        <a href="{{ route('admin.posts.create') }}
" class="btn btn-primary">
            + Nuevo Post
        </a>
    </div>

    @if($posts->count() == 0)
        <div class="alert alert-info">
            No hay contenidos aún.
        </div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            @if($post->imagen)
                                <img src="{{ asset('storage/' . $post->imagen) }}" alt="" style="width: 60px; height: 40px; object-fit: cover;" class="rounded">
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>{{ $post->titulo }}</td>
                        <td>
                            {{ $post->estado == 1 ? 'Activo' : 'Inactivo' }}

                        </td>
                        <td>
                            {{ $post->created_at->format('Y-m-d') }}
                        </td>
                        <td class="d-flex gap-1">

    @if($post->slug)
    <a href="{{ route('post.show', $post->slug) }}"
       class="btn btn-sm btn-info"
       target="_blank">
       Ver
    </a>
    @endif

    <!-- EDITAR (admin) -->
    <a href="{{ route('admin.posts.edit', $post->id) }}"
       class="btn btn-sm btn-warning">
       Editar
    </a>

    <!-- ELIMINAR (admin) -->
    <form action="{{ route('admin.posts.destroy', $post->id) }}"
          method="POST"
          onsubmit="return confirm('¿Seguro que deseas eliminar este post?')"
          style="display:inline;">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-sm btn-danger">
            Eliminar
        </button>
    </form>

</td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $posts->links() }}
    @endif

</div>
@endsection
