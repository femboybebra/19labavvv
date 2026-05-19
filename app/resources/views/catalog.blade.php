@extends('layouts.app')

@section('content')
<div class="content">

    <div class="head" style="margin-bottom: 10px">Наши товары</div>

    <form method="GET" action="{{ route('catalog') }}">
        <div class="row" style="margin-bottom: 20px">
            <p>
                <a href="{{ route('catalog') }}">Сброс</a> |
                <a href="{{ route('catalog', array_merge(request()->query(), ['sort'=>'year', 'dir'=> request('sort')=='year' && request('dir')=='asc' ? 'desc' : 'asc'])) }}">Год</a> |
                <a href="{{ route('catalog', array_merge(request()->query(), ['sort'=>'name', 'dir'=> request('sort')=='name' && request('dir')=='asc' ? 'desc' : 'asc'])) }}">Наименование</a> |
                <a href="{{ route('catalog', array_merge(request()->query(), ['sort'=>'price', 'dir'=> request('sort')=='price' && request('dir')=='asc' ? 'desc' : 'asc'])) }}">Цена</a>
                &uArr; &dArr;
            </p>
            <select id="category" name="category" onchange="this.form.submit()">
                <option value="" {{ !request('category') ? 'selected' : '' }}>Фильтрация по категориям</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="row" id="products">
        @forelse($products as $product)
        <div class="col">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
            @else
                <img src="/assets/images/logo/logo.png" alt="{{ $product->name }}">
            @endif
            <div class="row">
                <h3><a href="{{ route('product.show', $product) }}">{{ $product->name }}</a></h3>
                <p>{{ $product->price }}$</p>
                <input type="hidden" value="{{ $product->year }}" name="year">
                <input type="hidden" value="{{ $product->category->name ?? '' }}" name="category">
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
        @empty
            <p>Товары не найдены.</p>
        @endforelse
    </div>

</div>
@endsection
