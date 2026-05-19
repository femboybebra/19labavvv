@extends('layouts.app')

@section('content')
<div class="content">
    <div class="head" id="login">Вход</div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="text" placeholder="Логин" name="login" value="{{ old('login') }}" required>
        <input type="password" placeholder="Пароль" name="password" required>
        <button>Войти</button>
    </form>
    <p class="text-center" style="margin-top:15px">
        Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
    </p>
</div>
@endsection
