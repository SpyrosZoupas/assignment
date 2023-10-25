@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<h1>Shopping Cart</h1>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item['product']->description }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ $item['product']->price * $item['quantity'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if (session('discount'))
        Discounted Price: -{{$discount}}
    @endif
    <div>
        <strong>Total Price: ${{ $totalPrice }}</strong>
    </div>

    <form action="{{ route('discount') }}" method="POST">
    @csrf

    <div>
        <label for="discountCode">Discount Code</label>
        <input type="text" id="discountCode" name="discountCode">
    </div>

    <button type="submit">Apply Discount Code</button>
    </form>

<a href="{{ route('products') }}">Continue Shopping</a>

@guest
<a href="{{ route('register') }}">Register and become a user!</a>
@endguest

<form action="{{ route('checkout') }}" method="POST">
    @csrf
    <button type="submit">Checkout</button>
</form>

<form action="{{ route('clearCart') }}" method="POST">
        @csrf
        <button type="submit">Clear Cart</button>
    </form>
</div>
@endsection