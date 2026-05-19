@extends('layouts.app')

@section('content')
<div class="content">
    <div class="head">Новинки компании</div>

    <div id="slider">
        <div class="slides">
            @forelse($products as $product)
            <div class="slide col">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                @else
                    <img src="/assets/images/logo/logo.png" alt="{{ $product->name }}">
                @endif
                <h3><a href="{{ route('product.show', $product) }}">{{ $product->name }}</a></h3>
                <p>{{ $product->price }}$</p>
            </div>
            @empty
            <div class="col">
                <img src="/assets/images/logo/logo.png" alt="">
                <h3>Товары скоро появятся</h3>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/js/index.js"></script>
@endpush
