<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copy Star — Интернет-магазин</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    @stack('head')
</head>
<body>

<header>
    <div class="top">
        <div class="row">
            <img src="/assets/images/logo/logo.png" alt="logo">
            <a href="{{ route('home') }}">
                <h1>Copy Star</h1>
            </a>
        </div>
        <h2>Девиз компании!</h2>
    </div>
    <div class="content">
        <ul>
            <li><a href="{{ route('home') }}">О нас</a></li>
            <li><a href="{{ route('catalog') }}">Каталог</a></li>
            @auth
                <li><a href="{{ route('cart') }}">Корзина</a></li>
                <li><a href="{{ route('orders') }}">Мои заказы</a></li>
                @if(auth()->user()->is_admin)
                    <li><a href="{{ route('admin') }}">Администрирование</a></li>
                @endif
            @endauth
            <li><a href="{{ route('where') }}">Где нас найти?</a></li>
            @auth
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" style="max-width:80px">Выход</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}"><button>Вход</button></a></li>
                <li><a href="{{ route('register') }}"><button>Регистрация</button></a></li>
            @endauth
        </ul>
    </div>
</header>

@if(session('message'))
    <div class="message">{{ session('message') }}</div>
@endif

@if($errors->any())
    <div class="message" style="color:red">
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif

<main>
    @yield('content')
</main>

<footer>
    <div class="footer">
        Все права защищены | 2022
    </div>
</footer>

@stack('scripts')
</body>
</html>
