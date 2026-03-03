@extends('layouts.app')

@section('title', '로그인')

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="text" name="email" value="{{ old('email') }}">
        <input type="password" name="password">
        <input type="checkbox" name="remember">

        <button type="submit">로그인</button>

        <a href="{{ route('password.request') }}">비밀번호 재설정</a>

        {{-- @each: @foreach()를 사용하여 마크업을 표현해야 할 때, @foreach 대신 사용할 수 있는 단축 디렉티브 --}}
        @each('auth.social', $providers, 'provider')
    </form>
@endsection