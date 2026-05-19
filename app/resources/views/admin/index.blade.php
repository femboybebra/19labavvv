@extends('layouts.app')

@section('content')
<div class="content">

    <div class="head">Категории</div>
    <form method="POST" action="{{ route('admin.category.add') }}">
        @csrf
        <div class="part">
            <input type="text" placeholder="Название категории" name="name" required>
            <button>Добавить</button>
        </div>
    </form>
    <form method="POST" action="{{ route('admin.category.delete') }}">
        @csrf
        <div class="part">
            <select name="category_id" required>
                <option value="" disabled selected>Выберите категорию</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <button>Удалить</button>
        </div>
    </form>

    <div class="head">Добавить товар</div>
    <form method="POST" action="{{ route('admin.product.add') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" placeholder="Название" name="name" required>
        <input type="number" placeholder="Цена" name="price" required min="0">
        <input type="text" placeholder="Страна производитель" name="country" required>
        <input type="number" placeholder="Год выпуска" name="year" required min="1900" max="2100">
        <input type="text" placeholder="Модель" name="model" required>
        <select name="category_id" required>
            <option value="" disabled selected>Категория</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="number" placeholder="Количество на складе" name="count" required min="0">
        <p class="text-left">Фотография товара</p>
        <input type="file" name="image" accept="image/*">
        <button>Добавить</button>
    </form>

    <div class="head" style="margin-bottom: 10px">Заказы</div>
    <p style="margin-bottom: 20px">
        <a href="{{ route('admin') }}">Все</a> |
        <a href="{{ route('admin', ['status'=>'new']) }}">Новые</a> |
        <a href="{{ route('admin', ['status'=>'confirmed']) }}">Подтверждённые</a> |
        <a href="{{ route('admin', ['status'=>'cancelled']) }}">Отменённые</a>
    </p>

    @forelse($orders as $order)
    <div class="wrap" style="margin-bottom: 20px">
        <div class="row">
            <h2>Заказ #{{ $order->id }} — {{ $order->user->name }} {{ $order->user->surname }}</h2>
            <p class="text-right">
                <form class="wUnset" method="POST" action="{{ route('admin.order.delete') }}">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <button type="submit" class="text-small btnForm">Удалить заказ</button>
                </form>
            </p>
        </div>
        <div class="row">
            <p>Статус: <b>{{ $order->statusLabel() }}</b></p>
            <p>Количество товаров: <b>{{ $order->items->sum('quantity') }}</b></p>
            <p>Общая стоимость: <b>{{ $order->total_price }}$</b></p>
        </div>
        @if($order->cancel_reason)
        <div class="row">
            <p>Причина отмены: <b>{{ $order->cancel_reason }}</b></p>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.order.status') }}" style="display:flex; gap:10px; align-items:center; margin: 10px 0">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <select name="status" style="max-width:250px; margin:0">
                <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>Новый</option>
                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Подтверждённый</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Отменённый</option>
            </select>
            <input type="text" name="cancel_reason" placeholder="Причина отмены (если отменён)" style="margin:0">
            <button type="submit" style="max-width:180px; margin:0">Изменить статус</button>
        </form>

        <hr>
        <div class="row">
            @foreach($order->items as $item)
            <div class="col">
                <div class="row">
                    <h3><a href="{{ route('product.show', $item->product) }}">{{ $item->product->name }}</a></h3>
                    <p>{{ $item->price }}$</p>
                </div>
                <div class="row">
                    <p>Количество:</p>
                    <b>{{ $item->quantity }}</b>
                </div>
            </div>
            @endforeach
        </div>
    </div><br>
    @empty
        <p>Заказов нет.</p>
    @endforelse

</div>
@endsection
