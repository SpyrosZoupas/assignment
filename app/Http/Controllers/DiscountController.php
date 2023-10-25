<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function applyDiscount(Request $request) {        
        $discountCode = $request->input('discountCode');
        $discount = Discount::where('discountCode', $discountCode)->first();
    
        if ($discount) {
            if(session()->has('discount')) {
                return redirect(route('cart.show'))->with("error", "Only one discount allowed at the same time!");
            }
            if($discount->used == 1) {
                return redirect(route('cart.show'))->with("error", "Discount has already been used!");
            }
            session(['discount' => $discount->price]);
            session(['discountCode' => $discount->discountCode]);
            session(['discountId' => $discount->id]);
            return redirect()->route('cart.show')->with('success', 'Discount applied successfully.');
        } else {
            return redirect(route('cart.show'))->with("error", "Discount not found!");
        }        
    }
    
}

