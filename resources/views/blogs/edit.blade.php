@extends('layouts.app')

@section('title', '블로그 관리')

@section('content')
    <form action="{{ route('blogs.update', $blog) }}" method="POST">
        @method('PUT')
        @csrf

        <input type="text" id="name" name="name" value="{{ $blog->name }}">
        <input type="text" id="display_name" name="display_name" value="{{ $blog->display_name }}">

        <button type="submit">이름 바꾸기</button>
    </form>

    <form action="{{ route('blogs.destroy', $blog) }}" method="POST">
        @method('DELETE')
        @csrf
        
        <button type="submit">삭제</button>
    </form>
@endsection