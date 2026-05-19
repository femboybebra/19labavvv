@extends('layouts.app')

@section('content')
<div class="content">
    <div class="head" id="register">Регистрация</div>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" placeholder="Имя" name="name" value="{{ old('name') }}"
               pattern="[а-яА-ЯёЁ\s\-]+" required>
        <input type="text" placeholder="Фамилия" name="surname" value="{{ old('surname') }}"
               pattern="[а-яА-ЯёЁ\s\-]+" required>
        <input type="text" placeholder="Отчество" name="patronymic" value="{{ old('patronymic') }}"
               pattern="[а-яА-ЯёЁ\s\-]+">
        <input type="text" placeholder="Логин" name="login" value="{{ old('login') }}"
               pattern="[a-zA-Z0-9\-]+" required>
        <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
        <input type="password" placeholder="Пароль" name="password" pattern=".{6,}" required>
        <input type="password" placeholder="Повтор пароля" name="password_confirmation" required>
        <div class="part">
            <input type="checkbox" name="rules" required>
            <p>Согласие с правилами регистрации</p>
        </div>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p class="text-center" style="margin-top:15px">
        Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
    </p>
</div>
@endsection
