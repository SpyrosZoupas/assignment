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

    public function clearCart()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
        } else {
            $userId = 'guest';
        }

        Session::forget($userId . 'cart');

        return redirect()->route('cart.show')->with('success', 'Cart cleared successfully.');
    }
    
    public function checkout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
        } else {
            return redirect()->route('registration')->with('success', 'Please register first.');
        }
    
        $cart = Session::get($userId . 'cart', []);
    
        try {
            DB::beginTransaction();
    
            $order = new Order();
            $order->user_id = $userId;
            $order->save();
    
            foreach ($cart as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->order_id = $order->id;
                $orderItem->save();
            }

            $discountCode = Session::get('discountCode');
            if ($discountCode) {
                $order->discount_id = Session::get('discountId');
                $order->save();
                Discount::where('discountCode', $discountCode)->update(['used' => 1]);
            }
            
            Session::forget($userId . 'cart');
            Session::forget('discount');

            //todo job
            //GenerateDiscountCode::dispatch()->delay(now()->addMinutes(15));

            DB::commit();
    
            return redirect()->route('cart.show')->with('success', 'Checkout successful.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('cart.show')->with('error', 'Checkout failed. Please try again.');
        }
    }
}
