<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class shopController extends Controller
{
    public function addtoCart(Request $req)
    {
        $product = Product::with('product_img')->find($req->id);
        if ($product) {
            $cartArray = Cart::content();
            $productExit = false;
            foreach ($cartArray as $cart) {
                if ($cart->id == $product->id) {
                    $productExit = true;
                }
            }
            if ($productExit) {
                session()->flash('error', 'Product alreay added in cart');
                return response()->json([
                    'status' => false,
                    'slug' => $product->slug,
                    'message' => "Product alreay added in cart"
                ]);
            } else {
                Cart::add($product->id, $product->title, $product->sku, $product->price, array('productImage' => (!empty($product->product_img)) ? $product->product_img->first() : ''));
                session()->flash('success', 'Product added in cart');
                return response()->json([
                    'status' => true,
                    'slug' => $product->slug,
                    'message' => "Product added in cart"
                ]);
            }
        } else {
            session()->flash('error', 'Product not exit');
            return response()->json([
                'status' => false,
                'message' => "No product found"
            ]);
        }
    }
    public function cart()
    {
       $cart = Cart::content();
    //    dd($cart);   
        return view('front.cart',['cart' => $cart]);
    }
}
