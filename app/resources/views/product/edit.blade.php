@extends('layouts.app')

@section('content')
<div class="content">

    <div class="head">Изменить товар</div>
    <form method="POST" action="{{ route('product.update', $product) }}" enctype="multipart/form-data">
        @csrf
        <input type="text" placeholder="Название" name="name" required value="{{ old('name', $product->name) }}">
        <input type="number" placeholder="Цена" name="price" required value="{{ old('price', $product->price) }}" min="0">
        <input type="text" placeholder="Страна производитель" name="country" required value="{{ old('country', $product->country) }}">
        <input type="number" placeholder="Год выпуска" name="year" required value="{{ old('year', $product->year) }}" min="1900" max="2100">
        <input type="text" placeholder="Модель" name="model" required value="{{ old('model', $product->model) }}">
        <select name="category_id" required>
            <option value="" disabled>Категория</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="number" placeholder="Количество на складе" name="count" required value="{{ old('count', $product->count) }}" min="0">
        <p class="text-left">Текущая фотография товара</p>
        @if($product->image)
            <img src="{{ Storage::url($product->image) }}" alt="product" style="max-height:200px; margin-bottom:10px">
        @else
            <img src="/assets/images/logo/logo.png" alt="product" style="max-height:200px; margin-bottom:10px">
        @endif
        <p class="text-left">Новая фотография товара (необязательно)</p>
        <input type="file" name="image" accept="image/*">
        <button>Изменить</button>
    </form>

</div>
@endsection
