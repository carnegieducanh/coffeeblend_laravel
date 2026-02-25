<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product\Cart;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProductsController extends Controller
{
    public function singleProduct($id)
    {
        $product = Product::find($id);

        $relatedProducts = Product::where('type', $product->type)
            ->where('id', '!=', $id)->take(4)
            ->orderBy('id', 'desc')
            ->get();

        if (isset(Auth::user()->id)) {

            //checking for products in cart

            $checkingInCart = Cart::where('pro_id', $id)
                ->where('user_id', Auth::user()->id)
                ->count();

            return view('products.productsingle', compact('product', 'relatedProducts', 'checkingInCart'));
        } else {
            return view('products.productsingle', compact('product', 'relatedProducts', 'checkingInCart'));
        }
    }

    public function addCart(Request $request, $id)
    {

        $addCart = Cart::create([
            "pro_id" => $request->pro_id,
            "name" => $request->name,
            "image" => $request->image,
            "price" => $request->price,
            "user_id" => Auth::user()->id,
        ]);

        return Redirect::route('product.single', $id)->with(['success' => "product added to cart succesffully"]);
    }


    public function cart()
    {

        $cartProducts = Cart::where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->get();


        $totalPrice = Cart::where('user_id', Auth::user()->id)
            ->sum('price');

        return view('products.cart', compact('cartProducts', 'totalPrice'));
    }

    public function deleteProductCart($id)
    {

        $deleteProducCart = Cart::where('pro_id', $id)
            ->where('user_id', Auth::user()->id);


        $deleteProducCart->delete();


        if ($deleteProducCart) {
            return Redirect::route('cart')->with(['delete' => "product deleted from cart succesffully"]);
        }
    }
}