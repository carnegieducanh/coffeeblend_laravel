<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Product\Booking;
use App\Models\Product\Order;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

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

        // Tìm admin theo email trước để kiểm tra role
        $admin = Admin::where('email', $request->input('email'))->first();
        if ($admin && $admin->isSuperAdmin()) {
            return redirect()->back()->with(['error' => 'Tài khoản Super Admin phải đăng nhập bằng Google.']);
        }

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
            return redirect()->route('admins.dashboard');
        }
        return redirect()->back()->with(['error' => 'Email hoặc mật khẩu không đúng.']);
    }


    public function firebaseLogin(Request $request)
    {
        $idToken = $request->input('id_token');

        if (!$idToken) {
            return response()->json(['error' => 'Token không hợp lệ.'], 400);
        }

        // Verify token với Firebase REST API
        $response = Http::post(
            'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . config('services.firebase.api_key'),
            ['idToken' => $idToken]
        );

        if (!$response->successful() || empty($response->json()['users'])) {
            return response()->json(['error' => 'Xác thực Firebase thất bại.'], 401);
        }

        $firebaseUser = $response->json()['users'][0];
        $email        = $firebaseUser['email'] ?? null;

        if (!$email || $email !== config('services.firebase.super_admin_email')) {
            return response()->json(['error' => 'Email này không có quyền Super Admin.'], 403);
        }

        // Tìm hoặc tạo mới tài khoản super admin trong DB
        $admin = Admin::firstOrCreate(
            ['email' => $email],
            [
                'name'         => $firebaseUser['displayName'] ?? 'Super Admin',
                'firebase_uid' => $firebaseUser['localId'],
                'role'         => 'super_admin',
                'password'     => Hash::make(Str::random(40)),
            ]
        );

        // Cập nhật firebase_uid nếu chưa có
        if (!$admin->firebase_uid) {
            $admin->update([
                'firebase_uid' => $firebaseUser['localId'],
                'role'         => 'super_admin',
            ]);
        }

        auth()->guard('admin')->login($admin);
        $request->session()->regenerate();

        return response()->json(['success' => true, 'redirect' => route('admins.dashboard')]);
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
            "name"     => "required|max:40",
            "email"    => "required|max:40",
            "password" => "required|max:40",
        ]);

        $storeAdmins = Admin::Create([
            "name"     => $request->name,
            "email"    => $request->email,
            "password" => Hash::make($request->password),
            "role"     => 'admin',
        ]);

        if ($storeAdmins) {
            return Redirect::route('all.admins')->with(['success' => "Admin tạo thành công."]);
        }
    }


    public function deleteAdmins($id)
    {
        $admin = Admin::find($id);

        if (!$admin || $admin->isSuperAdmin()) {
            return Redirect::route('all.admins')->with(['error' => 'Không thể xóa tài khoản này.']);
        }

        $admin->delete();

        return Redirect::route('all.admins')->with(['success' => 'Admin đã được xóa thành công.']);
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
            "description" => $request->description ?? '',
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


    // ── User (Customer) Management ───────────────────────────────────────────

    public function displayUsers()
    {
        $users = User::orderBy('id', 'desc')->paginate(15);

        return view('admins.allusers', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('admins.edituser', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return Redirect::route('all.users')->with(['success' => 'User updated successfully.']);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return Redirect::route('all.users')->with(['success' => 'User deleted successfully.']);
    }
}
