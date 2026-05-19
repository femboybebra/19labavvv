@extends('layouts.app')

@section('content')
<form class="w100" method="POST" action="{{ route('cart.order') }}">
    @csrf
    <div class="content">

        <div class="head">Ваша корзина</div>

        @if($items->isEmpty())
            <h3 class="text-center">Корзина пуста</h3>
        @else
        <div class="wrap">
            <div class="row">
                @foreach($items as $item)
                <div class="col">
                    @if($item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}">
                    @else
                        <img src="/assets/images/logo/logo.png" alt="{{ $item->product->name }}">
                    @endif
                    <div class="row">
                        <h3><a href="{{ route('product.show', $item->product) }}">{{ $item->product->name }}</a></h3>
                        <p>{{ $item->product->price }}$</p>
                    </div>
                    <div class="row">
                        <form class="wUnset" method="POST" action="{{ route('cart.update') }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                            <button type="submit" class="text-small btnForm">Убрать</button>
                        </form>
                        <p>{{ $item->quantity }}</p>
                        <form class="wUnset" method="POST" action="{{ route('cart.update') }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                            <button type="submit" class="text-small btnForm">Добавить</button>
                        </form>
                    </div>
                    <p class="text-right">
                        <form method="POST" action="{{ route('cart.remove') }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button type="submit" class="text-small btnForm">Удалить из корзины</button>
                        </form>
                    </p>
                </div>
                @endforeach
            </div>
            <hr>
            <div class="row">
                <p>Общая стоимость:</p>
                <h2>{{ $total }}$</h2>
            </div>

            <input type="password" placeholder="Ваш пароль" name="password" required>
            <button>Сформировать заказ</button>
        </div>
        @endif

    </div>
</form>
@endsection
