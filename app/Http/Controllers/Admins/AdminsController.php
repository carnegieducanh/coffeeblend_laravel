<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Product\Booking;
use App\Models\Product\Order;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AdminsController extends Controller
{


    public function viewLogin()
    {
        return view('admins.login');
    }


    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('view.login');
    }


    public function checkLogin(Request $request)
    {

        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {

            return redirect()->route('admins.dashboard');
        }
        return redirect()->back()->with(['error' => 'error logging in']);
    }


    public function index()
    {

        $productsCount = Product::select()->count();
        $orderssCount = Order::select()->count();
        $bookingsCount = Booking::select()->count();
        $adminsCount = Admin::select()->count();

        return view('admins.index', compact('productsCount', 'orderssCount', 'bookingsCount', 'adminsCount'));
    }



    public function displayAllAdmins()
    {

        $allAdmins = Admin::select()->orderBy('id', 'desc')->get();

        return view('admins.alladmins', compact('allAdmins'));
    }


    public function createAdmins()
    {

        return view('admins.createadmins');
    }



    public function storeAdmins(Request $request)
    {

        Request()->validate([
            "name" => "required|max:40",
            "email" => "required|max:40",
            "password" => "required|max:40",

        ]);

        $storeAdmins = Admin::Create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        if ($storeAdmins) {
            return Redirect::route('all.admins')->with(['success' => "admin created succesffully"]);
        }
    }



    ///orders


    public function displayAllOrders()
    {
        $allOrders = Order::select()->orderBy('id', 'desc')->get();

        return view('admins.allorders', compact('allOrders'));
    }


    public function editOrders($id)
    {
        $order = Order::find($id);

        return view('admins.editorders', compact('order'));
    }


    public function UpdateOrders(Request $request, $id)
    {

        $order = Order::find($id);

        $order->update($request->all());

        if ($order) {

            return Redirect::route('all.orders')->with(['update' => "order status updated succesffully"]);
        }
    }


    public function deleteOrders($id)
    {
        $order = Order::find($id);

        $order->delete();

        if ($order) {

            return Redirect::route('all.orders')->with(['delete' => "order deleted succesffully"]);
        }
    }


    public function displayProducts()
    {
        $products = Product::orderBy('id', 'desc')->paginate(7);

        return view('admins.allproducts', compact('products'));
    }


    public function createProducts()
    {

        return view('admins.createproducts');
    }


    public function storeProducts(Request $request)
    {
        $request->validate([
            'name'      => 'required|max:255',
            'price'     => 'required',
            'image_url' => 'required|string',
            'type'      => 'required',
        ], [
            'image_url.required' => 'Ảnh sản phẩm chưa được upload lên Firebase. Vui lòng chọn ảnh và thử lại.',
        ]);

        $storeProducts = Product::Create([
            "name"        => $request->name,
            "price"       => $request->price,
            "image"       => $request->image_url,  // Firebase Storage download URL
            "description" => $request->description,
            "type"        => $request->type,
        ]);

        if ($storeProducts) {
            return Redirect::route('all.products')->with(['success' => "product created succesffully"]);
        }
    }

    public function deleteProducts($id)
    {
        $product = Product::find($id);

        // Only delete local file if image is not a Firebase URL
        if ($product->image && !str_starts_with($product->image, 'http')) {
            if (File::exists(public_path('assets/images/' . $product->image))) {
                File::delete(public_path('assets/images/' . $product->image));
            }
        }

        $product->delete();

        if ($product) {
            return Redirect::route('all.products')->with(['delete' => "product deleted succesffully"]);
        }
    }



    public function displayBookings()
    {

        $bookings = Booking::select()->orderBy('id', 'desc')->get();

        return view('admins.allbookings', compact('bookings'));
    }

    public function editBooking($id)
    {
        $booking = Booking::find($id);

        return view('admins.editbooking', compact('booking'));
    }



    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::find($id);

        $booking->update($request->all());

        if ($booking) {

            return Redirect::route('all.bookings')->with(['update' => "booking status updated successfully"]);
        }
    }

    public function deleteBooking($id)
    {

        $booking = Booking::find($id);

        $booking->delete();


        if ($booking) {
            return Redirect::route('all.bookings')->with(['delete' => "booking deleted succesffully"]);
        }
    }
}
