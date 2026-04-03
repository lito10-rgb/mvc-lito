@extends('layouts.app')

@section('title', 'Blog | Mono Tingales')

@section('content')
<div class="container">
    <h1 class="mb-4">Blog Mono Tingales</h1>

    <div class="row">
        @foreach($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $post->titulo }}</h5>

                <p class="card-text">
                    {{ Str::limit(strip_tags($post->cuerpo), 120) }}
                </p>

                @if($post->slug)
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-dark">
                        Leer más
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach

    </div>

    {{ $posts->links() }}
</div>
@endsection
