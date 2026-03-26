@extends('layouts.app')

@section('title', $blog->display_name)

@section('content')
    <h3>{{ $blog->display_name }}</h3>
    @auth
        <ul>
            <li><a href="{{ route('blogs.edit', $blog) }}">블로그 관리</a></li>
        </ul>
    @endauth
@endsection