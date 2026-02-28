<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product\Cart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * After successful login, handle any pending cart item saved before login.
     */
    protected function authenticated(Request $request, $user)
    {
        if (Session::has('pending_cart')) {
            $pending   = Session::pull('pending_cart');
            $productId = $pending['product_id'];

            $alreadyInCart = Cart::where('pro_id', $pending['pro_id'])
                ->where('user_id', $user->id)
                ->count();

            if ($alreadyInCart === 0) {
                Cart::create([
                    'pro_id'  => $pending['pro_id'],
                    'name'    => $pending['name'],
                    'image'   => $pending['image'],
                    'price'   => $pending['price'],
                    'user_id' => $user->id,
                ]);
            }

            return redirect()->route('product.single', $productId)
                ->with('success', __('messages.flash_login_with_cart'));
        }

        return redirect()->route('home')
            ->with('success', __('messages.flash_login_success', ['name' => $user->name]));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', __('messages.flash_logout_success'));
    }
}
