@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <header>
        <h1>{{ $post->title }}</h1>

        @can(['update', 'delete'], $post)
            <ul>
                <li>
                    <a href="{{ route('posts.edit', $post) }}">수정</a>
                </li>
                <li>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit">삭제</button>
                    </form>
                </li>
            </ul>
        @endcan
    </header>

    <article>{{ $post->content }}</article>
@endsection