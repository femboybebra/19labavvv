@extends('layouts.app')

@section('content')
<div class="content">

    <div class="head" style="margin-bottom: 10px">Мои заказы</div>

    <p style="margin-bottom: 20px">
        <a href="{{ route('orders') }}">Все</a> |
        <a href="{{ route('orders', ['status'=>'new']) }}">Новые</a> |
        <a href="{{ route('orders', ['status'=>'confirmed']) }}">Подтверждённые</a> |
        <a href="{{ route('orders', ['status'=>'cancelled']) }}">Отменённые</a>
    </p>

    @forelse($orders as $order)
    <div class="wrap" style="margin-bottom: 20px">
        <div class="row">
            <h2>Заказ #{{ $order->id }}</h2>
            <p class="text-right">
                <form method="POST" action="{{ route('orders.delete') }}" class="wUnset">
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
            <p>Причина отмены:</p>
            <p><b>{{ $order->cancel_reason }}</b></p>
        </div>
        @endif
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
        <p class="text-center">Заказов нет.</p>
    @endforelse

</div>
@endsection
