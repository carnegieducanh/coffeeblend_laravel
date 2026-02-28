<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Product\Booking;
use App\Models\Product\Order;
use App\Models\Product\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;


class UsersController extends Controller
{
    public function displayOrders()
    {

        $orders = Order::select()->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        return view('users.orders', compact('orders'));
    }


    public function displayBookings()
    {

        $bookings = Booking::select()->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        return view('users.bookings', compact('bookings'));
    }


    public function myAccount()
    {
        return view('users.account');
    }

    public function updateAccount(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => __('messages.flash_wrong_password')])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', __('messages.flash_account_updated'));
    }

    public function writeReview()
    {
        return view('users.writereview');
    }


    public function proccessWriteReview(Request $request)
    {

        $writeReviews = Review::create([
            "name" => Auth::user()->name,
            "review" => $request->review,
        ]);



        if ($writeReviews) {
            return Redirect::route('write.reviews')->with(['reviews' => __('messages.flash_review_submitted')]);
        }
    }
}
