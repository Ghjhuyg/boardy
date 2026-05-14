@extends('layouts.app')
@section('title', 'Редактировать пост')
@section('content')
    <h1>Редактирование поста</h1>
    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Содержание</label>
            <textarea name="body" id="body" rows="5" class="form-control @error('body') is-invalid @enderror" required>{{ old('body', $post->body) }}</textarea>
            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
@endsection
