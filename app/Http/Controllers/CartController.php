<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addCartItem($productId) {
        $product = Product::find($productId);
        $userId = 'guest';

        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
        }

        if (!$product) {
            return redirect()->route('products')->with('error', 'Product not found.');
        }

        if (!session()->has('cart')) {
            Session::put($userId . 'cart', []);
        }
        
        $cart = Session::get($userId . 'cart', []);

        $existingItem = null;
    
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItem = $key;
                break;
            }
        }
    
        if ($existingItem !== null) {
            $cart[$existingItem]['quantity'] += 1;
        } else {
            $cart[] = [
                'product_id' => $product->id,
                'quantity' => 1,
            ];
        }
    
        Session::put($userId . 'cart', $cart);
    
        return redirect()->route('products')->with('success', 'Product added to cart.');
    }
    
    
    public function getCart()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
        } else {
            $userId = 'guest';
        }

        $cart = Session::get($userId . 'cart', []);

        $products = Product::whereIn('id', array_column($cart, 'product_id'))->get();
        $cartItems = [];
        $totalPrice = 0;

        foreach ($cart as $item) {
            $product = $products->firstWhere('id', $item['product_id']);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                ];

                $totalPrice += $product->price * $item['quantity'];
            }
        }

        $discount = session('discount');
        $totalPrice -= $discount;

        session(['totalPrice' => $totalPrice]);

        return view('cart.cart', ['cartItems' => $cartItems, 'totalPrice' => $totalPrice, 'discount' => $discount]);
    }
}
