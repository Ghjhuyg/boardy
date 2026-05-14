@extends('layouts.app')
@section('title', 'Лента постов')
@section('content')
    <h1>Лента постов</h1>
    @auth
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Создать пост</a>
    @endauth

    @forelse ($posts as $post)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h5>
                <p class="card-text">{{ Str::limit($post->body, 200) }}</p>
                <p class="card-text"><small class="text-muted">Автор: {{ $post->author->name }} · {{ $post->created_at->format('d.m.Y H:i') }}</small></p>
            </div>
        </div>
    @empty
        <p>Постов пока нет.</p>
    @endforelse

    {{ $posts->links() }}
@endsection
