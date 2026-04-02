@extends('layouts.app')

@section('title', '글 목록')

@section('content')
    <ul>
        @foreach($posts as $post)
            <li>
                <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
            </li>
        @endforeach
    </ul>

    {{ $posts->links() }}
@endsection