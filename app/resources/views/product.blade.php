@extends('layouts.app')

@section('content')
<div class="content">

    <div class="head">{{ $product->name }}</div>

    <div class="product wrap">
        <div class="image">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
            @else
                <img src="/assets/images/logo/logo.png" alt="{{ $product->name }}">
            @endif
        </div>
        <div class="text">
            <h3>Характеристики:</h3>
            <p>Категория: <b>{{ $product->category->name ?? '—' }}</b></p>
            <p>Страна: <b>{{ $product->country }}</b></p>
            <p>Год выпуска: <b>{{ $product->year }}</b></p>
            <p>Модель: <b>{{ $product->model }}</b></p>
            <p>В наличии: <b>{{ $product->count }} шт.</b></p>
            <hr>
            <div class="row">
                <p>Цена:</p>
                <h3>{{ $product->price }}$</h3>
            </div>
            @auth
                @if(auth()->user()->is_admin)
                <div class="row">
                    <form class="wUnset" method="GET" action="{{ route('product.edit', $product) }}">
                        <button type="submit" class="text-small btnForm">Редактировать</button>
                    </form>
                    <form class="wUnset" method="POST" action="{{ route('admin.product.delete') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="text-small btnForm">Удалить</button>
                    </form>
                </div>
                @endif
                <p class="text-right">
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="text-small btnForm">В корзину</button>
                    </form>
                </p>
            @endauth
        </div>
    </div>

</div>
@endsection
