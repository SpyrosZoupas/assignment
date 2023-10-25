@extends('layouts.app')

@section('title', 'Products Page')

@section('content')

<h1>All Products</h1>
<div>
    @foreach($products as $product)
        @if($product->inStock == true)
        <div>
            <p>Description: {{ $product->description }}</p>
            <p>Price: ${{ $product->price }}</p>

            <form action="{{ route('cart.post', ['productId' => $product->id]) }}" method="POST">
                @csrf
                <button type="submit">Add to Cart</button>
            </form>
        </div>
        @endif
    @endforeach
</div>
@endsection