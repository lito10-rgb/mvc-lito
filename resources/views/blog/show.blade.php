@extends('layouts.app')

@section('title', $post->titulo)
@section('meta')
<meta property="og:title" content="{{ $post->titulo }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($post->cuerpo), 150) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
@endsection

@section('content')
<div class="container">
    <article class="blog-post">
        <h1 class="mb-3">{{ $post->titulo }}</h1>

        <p class="text-muted">
            Publicado el {{ $post->created_at->format('d M Y') }}
        </p>

        @if($post->imagen)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $post->imagen) }}" alt="{{ $post->titulo }}" class="img-fluid rounded">
            </div>
        @endif

        <div class="mt-4">
            {!! $post->cuerpo !!}
        </div>

        @php
            $url = url()->current();
            $title = $post->titulo;
        @endphp

        <div class="mt-4">
            <strong>Compartir:</strong>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
               target="_blank" class="btn btn-primary btn-sm">Facebook</a>
            <a href="https://wa.me/?text={{ urlencode($title . ' ' . $url) }}"
               target="_blank" class="btn btn-success btn-sm">WhatsApp</a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($title) }}&url={{ urlencode($url) }}"
               target="_blank" class="btn btn-dark btn-sm">X</a>
        </div>

        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary mt-4">
            ← Volver al blog
        </a>
    </article>
</div>
@endsection
