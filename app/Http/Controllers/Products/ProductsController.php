<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product\Booking;
use App\Models\Product\Cart;
use App\Models\Product\Order;
use App\Models\Product\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    public function singleProduct($id)
    {
        $product = Product::find($id);

        $relatedProducts = Product::where('type', $product->type)
            ->where('id', '!=', $id)->take(4)
            ->orderBy('id', 'desc')
            ->get();

        $checkingInCart = 0;

        if (Auth::check()) {
            $checkingInCart = Cart::where('pro_id', $id)
                ->where('user_id', Auth::user()->id)
                ->count();
        }

        return view('products.productsingle', compact('product', 'relatedProducts', 'checkingInCart'));
    }

    public function addCart(Request $request, $id)
    {
        if (!Auth::check()) {
            Session::put('pending_cart', [
                'pro_id'     => $request->pro_id,
                'name'       => $request->name,
                'image'      => $request->image,
                'price'      => $request->price,
                'product_id' => $id,
            ]);

            return Redirect::route('login')
                ->with('login_required', __('messages.flash_login_required'));
        }

        Cart::create([
            "pro_id"   => $request->pro_id,
            "name"     => $request->name,
            "image"    => $request->image,
            "price"    => $request->price,
            "user_id"  => Auth::user()->id,
        ]);

        return Redirect::route('product.single', $id)->with('success', __('messages.flash_cart_added'));
    }


    public function cart()
    {

        $cartProducts = Cart::where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->get();

        $totalPrice = Cart::where('user_id', Auth::user()->id)
            ->sum('price');

        $cartProIds = $cartProducts->pluck('pro_id')->toArray();
        $relatedProducts = Product::whereNotIn('id', $cartProIds)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.cart', compact('cartProducts', 'totalPrice', 'relatedProducts'));
    }

    public function deleteProductCart($id)
    {

        $deleteProducCart = Cart::where('pro_id', $id)
            ->where('user_id', Auth::user()->id);


        $deleteProducCart->delete();


        if ($deleteProducCart) {
            return Redirect::route('cart')->with(['delete' => __('messages.flash_cart_deleted')]);
        }
    }

    public function prepareCheckout(Request $request)
    {

        $value = $request->price;

        // lưu giá trị price vào session để sử dụng ở các request tiếp theo (ví dụ khi chuyển sang trang thanh toán).
        Session::put('price', $value);  // Chỉ lưu, không gán return value

        $newPrice = Session::get('price');  // Lấy đúng bằng key 'price'

        if ($newPrice > 0) {
            return Redirect::route('checkout');
        }
    }

    public function checkout()
    {
        return view('products.checkout');
    }

    public function storeCheckout(Request $request)
    {

        $checkout = Order::create($request->all());

        if ($checkout) {
            return Redirect::route('products.pay');
        }
    }

    public function payWithPaypal()
    {
        return view('products.pay');
    }

    public function success()
    {

        $deleteItems = Cart::where('user_id', Auth::user()->id);
        $deleteItems->delete();


        if ($deleteItems) {

            Session::forget('price');

            return view('products.success');
        }
    }

    public function BookTables(Request $request)
    {


        Request()->validate([
            "name" => "required|max:40",
            "date" => "required",
            "time" => "required",
            "phone" => "required|max:40",
            "message" => "required",
        ]);

        if ($request->date > date('n/j/Y')) {
            $bookTables = Booking::create($request->all());

            if ($bookTables) {
                return Redirect::route('home')->with(['booking' => __('messages.flash_booking_success')]);
            }
        } else {
            return Redirect::route('home')->with(['date' => __('messages.flash_booking_invalid_date')]);
        }
    }

    public function menu()
    {

        $desserts = Product::select()->where("type", "desserts")->orderBy('id', 'desc')->get();

        $drinks = Product::select()->where("type", "drinks")->orderBy('id', 'desc')->get();


        return view('products.menu', compact('desserts', 'drinks'));
    }
}