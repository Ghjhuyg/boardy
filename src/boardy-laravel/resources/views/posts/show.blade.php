@extends('layouts.app')
@section('title', $post->title)
@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h1>{{ $post->title }}</h1>
            <p class="text-muted">
                Автор: {{ $post->author->name }} · {{ $post->created_at->format('d.m.Y H:i') }}
            </p>
            <p>{{ $post->body }}</p>

            @can('update', $post)
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning">Редактировать</a>
            @endcan
            @can('delete', $post)
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пост?')">Удалить</button>
                </form>
            @endcan
        </div>
    </div>

    <h3>Комментарии</h3>
    @forelse ($post->comments as $comment)
        <div class="border p-2 mb-2">
            <strong>{{ $comment->author->name }}</strong> · <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
            <p>{{ $comment->body }}</p>
        </div>
    @empty
        <p>Комментариев пока нет.</p>
    @endforelse

    @auth
        <div class="mt-4">
            <h4>Добавить комментарий</h4>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <div class="mb-3">
                    <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="3" required></textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    @else
        <p><a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.</p>
    @endauth
@endsection
