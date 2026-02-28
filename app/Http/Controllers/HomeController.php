<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Review;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['index', 'services', 'about', 'contact']);
    //     // Dùng except() để loại trừ các trang công khai không cần đăng nhập:
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::select()->orderBy('id', 'desc')->take('4')->get();
        $reviews = Review::select()->orderBy('id', 'desc')->take('4')->get();

        return view('home.index', compact('products', 'reviews'));
    }

    public function services()
    {
        return view('pages.services');
    }


    public function contact()
    {
        return view('pages.contact');
    }


    public function about()
    {
        $reviews = Review::select()->orderBy('id', 'desc')->get();
        return view('pages.about', compact('reviews'));
    }
}
