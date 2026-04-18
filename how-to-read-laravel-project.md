# Hướng Dẫn Đọc Hiểu Dự Án Laravel: CoffeeBlend

> Viết cho level Junior (~1-2 năm kinh nghiệm). Bám sát source code thực tế của dự án.

---

## 1. TỔNG QUAN DỰ ÁN

### Đây là hệ thống gì?

**CoffeeBlend** là một **E-Commerce + Booking System** cho quán cà phê, bao gồm:
- Trang giới thiệu quán (Home, About, Services, Contact)
- Menu sản phẩm (thức uống, bánh ngọt, burger)
- Giỏ hàng và đặt hàng online
- Đặt bàn (Table Booking)
- Viết đánh giá (Review)
- Trang quản trị Admin (quản lý sản phẩm, đơn hàng, booking, admin khác, khách hàng)
- Hệ thống phân quyền Admin: **Super Admin** (đăng nhập Google/Firebase) + **Sub-Admin** (email/password)
- Đa ngôn ngữ: Tiếng Anh / Tiếng Nhật

### Thông tin kỹ thuật nhanh

| Thông tin | Giá trị |
|---|---|
| Framework | **Laravel 12.0** |
| PHP | ^8.2 |
| Frontend | Blade Templates + Bootstrap + Template Colorlib |
| Database | MySQL (qua XAMPP) |
| Auth Package | `laravel/ui` v4.6 |
| Kiến trúc | **Monolithic MVC** |
| API? | Không có API riêng (không dùng Sanctum/Passport) |
| Deployment | Render.com (có TrustProxies middleware) |

### Kiến trúc tổng quát

```
Monolithic MVC
├── Model     → Eloquent ORM (tương tác DB)
├── View      → Blade Templates (HTML)
└── Controller → Xử lý logic request/response
```

**Không có** Service Layer, Repository Pattern, hay tách frontend (Vue/React). Đây là Laravel "truyền thống" — phù hợp để học.

---

## 2. CẤU TRÚC THƯ MỤC VÀ VAI TRÒ THỰC TẾ

```
coffeeblend_laravel/
│
├── app/                         ← LOGIC CHÍNH của ứng dụng
│   ├── Http/
│   │   ├── Controllers/         ← Xử lý request, gọi Model, trả về View
│   │   │   ├── Controller.php           (Base controller)
│   │   │   ├── HomeController.php       (Trang chủ, about, services, contact)
│   │   │   ├── LanguageController.php   (Đổi ngôn ngữ EN/JP)
│   │   │   ├── Products/
│   │   │   │   └── ProductsController.php  (Menu, cart, checkout, booking)
│   │   │   ├── Users/
│   │   │   │   └── UsersController.php     (Orders, bookings, account, review)
│   │   │   ├── Admins/
│   │   │   │   └── AdminsController.php    (Toàn bộ trang quản trị)
│   │   │   └── Auth/                       (Login/Register - tự sinh bởi laravel/ui)
│   │   │
│   │   └── Middleware/          ← Lọc request trước khi vào Controller
│   │       ├── SetLocale.php        (Đặt ngôn ngữ từ session)
│   │       ├── CheckForPrice.php    (Chặn checkout nếu chưa có giá)
│   │       ├── CheckForAuth.php     (Chặn admin login nếu đã đăng nhập)
│   │       └── CheckSuperAdmin.php  (Chặn route quản lý admin nếu không phải Super Admin)
│   │
│   ├── Models/                  ← Đại diện bảng trong DB
│   │   ├── User.php
│   │   ├── Admin/
│   │   │   └── Admin.php
│   │   └── Product/
│   │       ├── Product.php
│   │       ├── Cart.php
│   │       ├── Order.php
│   │       ├── Booking.php
│   │       └── Review.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php  ← Khởi động dịch vụ app (ít dùng ở đây)
│
├── bootstrap/
│   └── app.php                  ← CẤU HÌNH KHỞI ĐỘNG: routing, middleware
│
├── config/                      ← Cấu hình ứng dụng
│   ├── app.php                  (timezone, locale, providers)
│   ├── auth.php                 (guards, providers - QUAN TRỌNG)
│   ├── database.php             (kết nối DB)
│   └── ...
│
├── database/
│   ├── migrations/              ← Lịch sử tạo/sửa bảng DB (đọc để hiểu schema)
│   └── seeders/                 ← Dữ liệu mẫu để test
│
├── resources/
│   ├── views/                   ← Blade Templates (HTML + PHP)
│   │   ├── layouts/             (Layout chung: app.blade.php, admin.blade.php)
│   │   ├── home/                (Các section trang chủ)
│   │   ├── pages/               (about, contact, services)
│   │   ├── products/            (menu, cart, checkout, pay, success)
│   │   ├── users/               (orders, bookings, account, writereview)
│   │   └── admins/              (toàn bộ trang admin)
│   │
│   ├── lang/                    ← File dịch ngôn ngữ
│   │   ├── en/messages.php      (~150 keys Tiếng Anh)
│   │   └── ja/messages.php      (~150 keys Tiếng Nhật)
│   │
│   └── sass/app.scss            ← SCSS (chỉ import Bootstrap)
│
├── routes/
│   └── web.php                  ← ĐỊNH NGHĨA TOÀN BỘ URL (entry point đọc code)
│
├── public/
│   ├── index.php                ← ENTRY POINT thực sự (mọi request vào đây)
│   └── assets/                  ← CSS/JS/Image tĩnh (template Colorlib)
│
└── storage/                     ← Logs, cache, file upload
    └── logs/laravel.log         (đọc khi có lỗi)
```

---

## 3. ENTRY POINT VÀ LUỒNG XỬ LÝ REQUEST

### Sơ đồ luồng tổng quát

```
Browser gửi request
        ↓
public/index.php          ← Mọi request đều đi qua đây (web server config)
        ↓
Laravel Bootstrap         ← bootstrap/app.php khởi động app
        ↓
HTTP Kernel               ← Chạy Global Middleware stack
        ↓
Router (routes/web.php)   ← Tìm route khớp với URL
        ↓
Route Middleware           ← Chạy middleware của route đó (auth, check.for.price...)
        ↓
Controller@method          ← Xử lý logic chính
        ↓
Model (Eloquent)           ← Truy vấn Database
        ↓
View (Blade)               ← Render HTML
        ↓
HTTP Response              ← Trả về browser
```

### Ví dụ thực tế: User xem trang Menu

**URL:** `GET /products/menu`

```
1. public/index.php  → bootstrap app

2. routes/web.php tìm thấy:
   Route::get('products/menu', [ProductsController::class, 'menu'])
        ->name('products.menu');

3. Không có middleware → vào thẳng Controller

4. ProductsController@menu():
   - Gọi Product::where('type', 'Drinks')->get()
   - Gọi Product::where('type', 'Desserts')->get()
   - return view('products.menu', compact('drinks', 'desserts', ...))

5. resources/views/products/menu.blade.php render HTML

6. Trả về HTML cho browser
```

### Ví dụ thực tế: User thêm vào giỏ hàng

**URL:** `POST /products/addcart`

```
1. routes/web.php:
   Route::post('products/addcart', [ProductsController::class, 'addCart'])
        ->name('products.addcart')
        ->middleware('auth:web');   ← Cần đăng nhập

2. Middleware auth:web kiểm tra:
   - Chưa login → redirect về /login
   - Đã login → cho qua

3. ProductsController@addCart():
   - Lấy dữ liệu từ $request (pro_id, name, price, image)
   - Cart::create([...]) → lưu vào bảng cart
   - return redirect()->route('products.cart')

4. Redirect → GET /products/cart → hiển thị giỏ hàng
```

---

## 4. THỨ TỰ ĐỌC SOURCE CODE (QUAN TRỌNG)

### Bước 1: Đọc `routes/web.php` — Bản đồ của ứng dụng

**Vì sao đọc trước?**
Routes là "bản đồ" cho thấy ứng dụng có những URL nào, URL nào cần auth, URL nào gọi Controller nào.

**Cần chú ý gì?**
```php
// routes/web.php

// 1. Đổi ngôn ngữ
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// 2. Trang công khai
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');

// 3. Nhóm Products (một số cần auth)
Route::get('products/menu', [ProductsController::class, 'menu'])->name('products.menu');
Route::post('products/addcart', [ProductsController::class, 'addCart'])
    ->name('products.addcart')
    ->middleware('auth:web');               // ← Cần đăng nhập user

// 4. Nhóm Users (tất cả cần auth)
Route::middleware('auth:web')->group(function () {
    Route::get('users/orders', [UsersController::class, 'displayOrders'])->name('users.orders');
    // ...
});

// 5. Nhóm Admin (guard riêng biệt)
Route::post('admin/firebase-login', [AdminsController::class, 'firebaseLogin'])
    ->name('admin.firebase.login');  // Nhận Firebase ID token, tạo session Laravel

Route::middleware('auth:admin')->group(function () {
    Route::get('admin/index', [AdminsController::class, 'index'])->name('admins.dashboard');

    // Chỉ Super Admin (middleware 'super.admin')
    Route::middleware('super.admin')->group(function () {
        Route::get('admin/all-admins',    [AdminsController::class, 'displayAllAdmins'])->name('all.admins');
        Route::get('admin/create-admins', [AdminsController::class, 'createAdmins'])->name('create.admins');
        Route::post('admin/create-admins',[AdminsController::class, 'storeAdmins'])->name('store.admins');
        Route::get('admin/delete-admin/{id}', [AdminsController::class, 'deleteAdmins'])->name('delete.admin');
    });

    // Mọi admin đều truy cập được
    Route::get('admin/all-users',    [AdminsController::class, 'displayUsers'])->name('all.users');
    Route::get('admin/edit-user/{id}',  [AdminsController::class, 'editUser'])->name('edit.user');
    Route::post('admin/edit-user/{id}', [AdminsController::class, 'updateUser'])->name('update.user');
    Route::get('admin/delete-user/{id}',[AdminsController::class, 'deleteUser'])->name('delete.user');
    // ... orders, products, bookings
});

// 6. Auth routes (login/register tự động)
Auth::routes();
```

**Đọc xong hiểu được gì?**
- Toàn bộ URL có trong app
- URL nào public, URL nào cần login
- Controller nào xử lý từng nhóm chức năng

---

### Bước 2: Đọc Controllers — Xử lý logic

**Vì sao đọc?**
Controller là nơi xử lý business logic: nhận request, gọi model, trả về view.

**Thứ tự đọc controller:**

**2a. `HomeController.php`** — Đơn giản nhất, đọc trước để làm quen

```php
// app/Http/Controllers/HomeController.php
public function index()
{
    $latestProducts = Product::latest()->take(4)->get();  // 4 sản phẩm mới nhất
    $latestReviews  = Review::latest()->take(4)->get();   // 4 review mới nhất
    return view('home.index', compact('latestProducts', 'latestReviews'));
}
```

**2b. `Products/ProductsController.php`** — Phức tạp hơn, có cart/checkout/booking

```php
// Thêm vào giỏ hàng
public function addCart(Request $request)
{
    Cart::create([
        'pro_id'  => $request->pro_id,
        'name'    => $request->name,
        'image'   => $request->image,
        'price'   => $request->price,
        'user_id' => Auth::id(),         // Lấy ID user đang login
    ]);
    return redirect()->route('products.cart');
}

// Đặt bàn (không cần login)
public function BookTables(Request $request)
{
    $request->validate([
        'date' => ['required', 'date', 'after:today'],  // Phải là ngày tương lai
        // ...
    ]);
    Booking::create([...]);
    return redirect()->back()->with('success', 'Booking confirmed!');
}
```

**2c. `Admins/AdminsController.php`** — Đọc sau cùng vì dài nhất

**Cần chú ý gì khi đọc Controller?**
- `$request->validate([...])` → validation rules
- `Model::create([...])` / `Model::find($id)` → thao tác DB
- `return view(...)` vs `return redirect()` → trả về gì
- `Auth::id()` / `Auth::user()` → dữ liệu user đang login
- `session([...])` → lưu dữ liệu tạm

---

### Bước 3: Đọc Models — Cấu trúc dữ liệu

**Vì sao đọc?**
Model định nghĩa bảng DB nào, cột nào được phép ghi, và các relationship.

```php
// app/Models/Product/Product.php
class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['name', 'image', 'price', 'description', 'description_ja', 'type'];
    // ↑ Chỉ những cột này mới được mass assignment (tránh lỗ hổng bảo mật)

    // Custom attribute: trả về mô tả đúng ngôn ngữ
    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() === 'ja' && $this->description_ja
            ? $this->description_ja
            : $this->description;
    }
}
```

```php
// app/Models/Admin/Admin.php — Guard riêng cho Admin
class Admin extends Authenticatable  // extends Authenticatable, không phải Model thường
{
    protected $fillable = ['name', 'email', 'password', 'role', 'firebase_uid'];
    protected $hidden   = ['password'];

    // Helper method: kiểm tra có phải Super Admin không
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}
// role: 'super_admin' | 'admin' (default: 'admin')
// firebase_uid: lưu UID từ Firebase nếu đăng nhập bằng Google (nullable)
```

**Cần chú ý gì?**
- `$fillable` — cột nào được phép ghi bằng `create()`/`fill()`
- `$hidden` — cột nào bị ẩn khi convert sang JSON (password!)
- `$casts` — tự động convert kiểu dữ liệu
- Custom accessor/mutator: `get{Name}Attribute()` / `set{Name}Attribute()`
- Dự án này **chưa có** Eloquent Relationships (hasMany/belongsTo) — mối quan hệ được xử lý thủ công trong Controller

---

### Bước 4: Đọc Migrations — Cấu trúc Database

**Vì sao đọc?**
Migrations là "lịch sử" tạo bảng — đọc để hiểu schema DB chính xác hơn nhìn vào phpMyAdmin.

**Schema tóm tắt:**

```
users
├── id, name, email, password, remember_token
└── email_verified_at, timestamps

admins
├── id, name, email, password
├── role (enum: 'super_admin' | 'admin', default: 'admin')
├── firebase_uid (varchar, nullable, unique)  ← UID Firebase của Super Admin
└── timestamps

products
├── id, name, image (text - URL Firebase), price (varchar)
├── description, description_ja (text, nullable)
├── type (varchar: 'Drinks'/'Desserts'/'Burgers')
└── timestamps

cart
├── id, pro_id (int), name, image, price
├── user_id (varchar 10)          ← Lưu ý: varchar, không phải foreign key!
└── timestamps

orders
├── id, name, state, address, city, zip_code
├── phone, email, price, user_id (int)
├── status (default: 'Processing')
└── timestamps

bookings
├── id, name, date, time, phone, message
├── user_id (varchar, nullable)    ← Nullable: không cần đăng nhập để đặt bàn
├── status (default: 'Processing')
└── timestamps

reviews
├── id, name, review
└── timestamps
```

**Quan trọng:** Dự án này **không có Foreign Key Constraints** — các `user_id`, `pro_id` chỉ là số nguyên thông thường, không có ràng buộc DB cấp thấp.

---

### Bước 5: Đọc Views (Blade) — Giao diện

**Vì sao đọc?**
View là thứ user nhìn thấy — đọc để hiểu cách data từ Controller được render.

**Cấu trúc layout:**

```php
// resources/views/layouts/app.blade.php — Layout chính
<!DOCTYPE html>
<html>
<head>
    @yield('title')        ← Trang con điền vào đây
</head>
<body data-lang="{{ session('app_locale', 'ja') }}">
    <!-- Navbar -->
    @yield('content')      ← Trang con điền nội dung chính vào đây
    <!-- Footer -->
</body>
</html>
```

```php
// resources/views/home/index.blade.php — Trang con
@extends('layouts.app')    ← Kế thừa layout

@section('content')        ← Điền vào @yield('content')
    @include('home._hero')             ← Include partial
    @include('home._bestsellers')
    @foreach($latestProducts as $product)
        {{ $product->name }}           ← In dữ liệu từ Controller
    @endforeach
@endsection
```

**Syntax Blade cơ bản cần biết:**

| Syntax | Ý nghĩa |
|---|---|
| `{{ $var }}` | In biến (auto-escape XSS) |
| `{!! $var !!}` | In biến (không escape - nguy hiểm nếu dùng sai) |
| `@if / @else / @endif` | Điều kiện |
| `@foreach / @endforeach` | Vòng lặp |
| `@extends('layout')` | Kế thừa layout |
| `@section('name') ... @endsection` | Định nghĩa section |
| `@yield('name')` | Vị trí chèn section |
| `@include('partial')` | Nhúng file khác |
| `@auth / @guest` | Kiểm tra đăng nhập |
| `{{ __('messages.key') }}` | Dịch ngôn ngữ |
| `{{ route('name') }}` | Generate URL |
| `{{ csrf_field() }}` hoặc `@csrf` | CSRF token (bắt buộc trong form POST) |

---

### Bước 6: Đọc Middleware và Auth

**Middleware trong dự án này:**

```php
// app/Http/Middleware/CheckSuperAdmin.php
// Chỉ chạy trên các route quản lý admin (all-admins, create-admins, delete-admin)
public function handle(Request $request, Closure $next): Response
{
    $admin = auth()->guard('admin')->user();
    if (!$admin || !$admin->isSuperAdmin()) {
        return redirect()->route('admins.dashboard')
            ->with('error', 'Bạn không có quyền truy cập chức năng này.');
    }
    return $next($request);
}
```

```php
// app/Http/Middleware/SetLocale.php
// Chạy trên MỌI request (web group)
public function handle(Request $request, Closure $next): Response
{
    $locale = session('app_locale', 'ja');  // Default là Tiếng Nhật!
    if (in_array($locale, ['en', 'ja'])) {
        App::setLocale($locale);
    }
    return $next($request);  // Tiếp tục xử lý request
}
```

```php
// app/Http/Middleware/CheckForPrice.php
// Chỉ chạy trên route /products/pay (xem routes/web.php)
public function handle(Request $request, Closure $next)
{
    if (Session::get('price') == 0) {
        return abort(403);   // Không có giá → chặn
    }
    return $next($request);
}
```

**Hệ thống Auth (2 Guard):**

```php
// config/auth.php — CỰC KỲ QUAN TRỌNG
'guards' => [
    'web' => [                    // Guard cho User thông thường
        'driver'   => 'session',
        'provider' => 'users',    // Dùng bảng users
    ],
    'admin' => [                  // Guard riêng cho Admin
        'driver'   => 'session',
        'provider' => 'admins',   // Dùng bảng admins
    ],
],
'providers' => [
    'users'  => ['driver' => 'eloquent', 'model' => User::class],
    'admins' => ['driver' => 'eloquent', 'model' => Admin::class],
],
```

Nghĩa là: User và Admin hoàn toàn tách biệt. Admin không thể đăng nhập bằng form user và ngược lại.

**Phân quyền trong Admin (2 role):**

| Role | Đăng nhập | Quyền |
|---|---|---|
| `super_admin` | Google Sign-In (Firebase) | Toàn quyền: xem + quản lý admin khác |
| `admin` | Email / Password (DB) | Xem orders, products, bookings, customers — KHÔNG quản lý admin |

- Super Admin được tạo tự động trong DB lần đầu đăng nhập Google thành công
- Email Super Admin phải khớp với `FIREBASE_SUPER_ADMIN_EMAIL` trong `.env`
- Super Admin không thể đăng nhập bằng email/password (hệ thống chặn)
- Sub-Admin không thể truy cập `/admin/all-admins`, `/admin/create-admins`, `/admin/delete-admin/{id}`

---

## 5. PHÂN TÍCH CÁC PHẦN QUAN TRỌNG

### 5.1 Routing

```php
// routes/web.php — 4 nhóm chính

// Nhóm 1: Public (ai cũng truy cập được)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('products/menu', [ProductsController::class, 'menu'])->name('products.menu');

// Nhóm 2: Cần user login (auth:web guard)
Route::post('products/addcart', [ProductsController::class, 'addCart'])
    ->middleware('auth:web');

Route::middleware('auth:web')->group(function () {
    Route::get('users/orders', [UsersController::class, 'displayOrders'])->name('users.orders');
});

// Nhóm 3: Cần admin login (auth:admin guard)
Route::middleware('auth:admin')->group(function () {
    Route::get('/admins', [AdminsController::class, 'index'])->name('admins.index');
});

// Nhóm 4: Middleware tùy chỉnh
Route::get('/products/pay', [ProductsController::class, 'payWithPaypal'])
    ->name('products.pay')
    ->middleware('check.for.price');  // Phải có giá trong session
```

### 5.2 Eloquent ORM — Các câu lệnh thực tế trong dự án

```php
// Lấy tất cả
Product::all()

// Lấy với điều kiện
Product::where('type', 'Drinks')->get()
Product::where('user_id', Auth::id())->get()

// Lấy một record
Product::find($id)
Product::findOrFail($id)    // Tự 404 nếu không tìm thấy

// Tạo mới
Cart::create([
    'pro_id'  => $request->pro_id,
    'user_id' => Auth::id(),
]);

// Cập nhật
$order = Order::find($id);
$order->update(['status' => $request->status]);

// Xóa
Cart::where('user_id', Auth::id())->delete();  // Xóa nhiều
$booking->delete();                             // Xóa một

// Phân trang
Product::latest()->paginate(7)  // 7 item/trang
```

### 5.3 Validation

```php
// Trong Controller, validate trước khi xử lý
$request->validate([
    'name'     => 'required|string|max:200',
    'email'    => 'required|email',
    'password' => 'required|min:6|confirmed',  // confirmed: phải có field password_confirmation
    'date'     => 'required|date|after:today', // Phải là ngày tương lai
    'phone'    => 'required|regex:/^[0-9]{10,15}$/',
]);
// Nếu fail → tự động redirect back với errors
// Trong Blade: {{ $errors->first('field_name') }}
```

### 5.4 Session

```php
// Lưu vào session
session(['price' => $totalPrice]);
// hoặc
Session::put('app_locale', 'ja');

// Đọc từ session
Session::get('price');
session('app_locale', 'ja');  // 'ja' là default nếu không có

// Xóa khỏi session
Session::forget('price');
```

### 5.5 Flash Message

```php
// Controller: set flash message
return redirect()->back()->with('success', 'Booking confirmed!');
return redirect()->back()->with('error', 'Something went wrong!');

// Blade: hiển thị (resources/views/layouts/_flash_alerts.blade.php)
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

---

## 6. FLOW CÁC CHỨC NĂNG CHÍNH

### 6.1 Flow Mua Hàng (Shopping Flow)

```
User xem Menu
    ↓
GET /products/menu → ProductsController@menu
    → Product::where('type','Drinks')->get()
    → view('products.menu')
    ↓
User click "Add to Cart"
    ↓
POST /products/addcart (cần login)
    → [Middleware auth:web] → nếu chưa login: redirect /login
    → Cart::create([...user_id = Auth::id()...])
    → redirect route('products.cart')
    ↓
GET /products/cart → ProductsController@cart
    → Cart::where('user_id', Auth::id())->get()
    → Tính total price
    → view('products.cart')
    ↓
User click "Checkout"
    ↓
POST /products/prepare-checkout → ProductsController@prepareCheckout
    → session(['price' => $total])   ← Lưu giá vào session
    → redirect route('products.checkout')
    ↓
GET /products/checkout → view('products.checkout')
    ↓
POST /products/checkout → ProductsController@storeCheckout
    → Order::create([...user_id, price, address...])
    → redirect route('products.pay')
    ↓
GET /products/pay [middleware: check.for.price]
    → CheckForPrice: nếu session price = 0 → 403
    → view('products.pay')
    ↓
User click "Pay with PayPal" (simulation)
    ↓
GET /products/success → ProductsController@success
    → Cart::where('user_id', Auth::id())->delete()  ← Xóa giỏ hàng
    → Session::forget('price')
    → view('products.success')
```

### 6.2 Flow Đặt Bàn (Booking Flow)

```
User điền form đặt bàn (không cần đăng nhập)
    ↓
POST /products/booktables → ProductsController@BookTables
    → validate: date must be after today
    → Booking::create([
          user_id => Auth::check() ? Auth::id() : null,  ← Nullable
          date, time, name, phone, message
      ])
    → redirect back with success message
    ↓
Admin vào /admins/bookings xem danh sách
    → AdminsController@displayBookings
    → Booking::all()
    → Cập nhật status: 'Processing' → 'Confirmed' / 'Cancelled'
```

### 6.3 Flow Login/Register (User)

```
GET /login → Auth\LoginController → view('auth.login')
    ↓
POST /login → LoginController@login (Laravel built-in)
    → Validate email/password
    → Auth::guard('web')->attempt([...])
    → Nếu thành công: redirect /home
    → Nếu thất bại: redirect back with errors
    ↓
GET /register → RegisterController → view('auth.register')
    ↓
POST /register → RegisterController@register
    → Validate name, email, password
    → User::create([...])  ← Password tự hash (xem User model $casts)
    → Auto login → redirect /home
```

### 6.4 Flow Login Admin — Sub-Admin (email/password)

```
GET /admin/login → AdminsController@viewLogin
    [Middleware check.for.aut]: nếu đã login admin → redirect về dashboard
    → view('admins.login')
    ↓
POST /admin/login → AdminsController@checkLogin
    → Kiểm tra email: nếu role = 'super_admin' → từ chối, yêu cầu dùng Google
    → Auth::guard('admin')->attempt(['email'=>..., 'password'=>...], $remember)
    → Nếu thành công: redirect route('admins.dashboard')
    → Nếu thất bại: redirect back with error
```

### 6.5 Flow Login Super Admin (Firebase Google Sign-In) ← MỚI

```
GET /admin/login → view('admins.login')
    Trang hiển thị 2 phần:
    - Form email/password (dành cho sub-admin)
    - Nút "Đăng nhập với Google" (dành cho super admin)
    ↓
User click "Đăng nhập với Google"
    → Firebase JS SDK: firebase.auth().signInWithPopup(GoogleAuthProvider)
    → Google hiện popup đăng nhập
    → Firebase trả về ID Token (JWT)
    ↓
POST /admin/firebase-login → AdminsController@firebaseLogin
    → Gửi ID Token đến Firebase REST API để verify:
       https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=FIREBASE_API_KEY
    → Firebase xác nhận token hợp lệ, trả về thông tin user (email, uid, name)
    → Kiểm tra email có khớp với FIREBASE_SUPER_ADMIN_EMAIL trong .env không
    → Nếu không khớp → trả về 403 JSON error
    → Nếu khớp: Admin::firstOrCreate(['email'=>$email], [...role=>'super_admin'...])
       (Tạo record trong bảng admins nếu chưa có, lần sau dùng lại record cũ)
    → auth()->guard('admin')->login($admin)
    → Trả về JSON: { success: true, redirect: '/admin/index' }
    ↓
Frontend nhận JSON → window.location.href = data.redirect
    → Vào trang dashboard với quyền super admin
```

**Cấu hình cần thiết trong `.env`:**
```
FIREBASE_API_KEY=AIzaSy...           ← Web API Key từ Firebase Console
FIREBASE_SUPER_ADMIN_EMAIL=you@gmail.com  ← Email Google của Super Admin
```

### 6.6 Flow Quản Lý Khách Hàng (Customer Management) ← MỚI

```
Admin vào navbar → click "Customers"
    ↓
GET /admin/all-users → AdminsController@displayUsers
    → User::orderBy('id','desc')->paginate(15)
    → view('admins.allusers') — Bảng: id, tên, email, ngày đăng ký, nút Edit/Delete
    ↓
Sửa thông tin user:
    GET /admin/edit-user/{id} → AdminsController@editUser → view('admins.edituser')
    POST /admin/edit-user/{id} → AdminsController@updateUser
        → validate: name required, email unique (trừ chính user đó)
        → $user->update(['name'=>..., 'email'=>...])
        → redirect all.users with success
    ↓
Xóa user:
    GET /admin/delete-user/{id} → AdminsController@deleteUser
        → $user->delete()
        → redirect all.users with success
```

**Lưu ý:** Tính năng này chỉ cho sửa `name` và `email` — không cho đổi `password` vì lý do bảo mật.

### 6.7 Flow Đổi Ngôn Ngữ

```
User click "EN" hoặc "JP" trên navbar
    ↓
GET /lang/en (hoặc /lang/ja) → LanguageController@switch
    → session(['app_locale' => 'en'])
    → redirect()->back()
    ↓
Request tiếp theo
    → SetLocale middleware đọc session('app_locale')
    → App::setLocale('en')
    → __('messages.key') trả về text tiếng Anh
```

---

## 7. FLOW HOÀN CHỈNH (TEXT DIAGRAM)

```
┌─────────────────────────────────────────────────────────────────┐
│                         BROWSER                                  │
│  User gõ URL hoặc click link → HTTP Request                     │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                   public/index.php                               │
│  Entry point duy nhất — mọi request đều qua đây                │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                  bootstrap/app.php                               │
│  Khởi động app, đăng ký middleware groups                       │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│              Global Middleware Stack                              │
│  TrustProxies → PreventRequestsDuringMaintenance →              │
│  HandleCors → ValidatePostSize → TrimStrings →                  │
│  ConvertEmptyStrings                                             │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│              Web Middleware Group                                 │
│  EncryptCookies → AddQueuedCookies → StartSession →             │
│  ShareErrorsFromSession → VerifyCsrfToken →                     │
│  SubstituteBindings → SetLocale (custom)                        │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                   routes/web.php                                 │
│  Router tìm route khớp với Method + URL                         │
│  Chạy Route Middleware (auth:web, auth:admin, check.for.price)  │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Controller@method                              │
│  Nhận $request, validate input, xử lý business logic           │
└──────────────┬──────────────────────────────┬───────────────────┘
               │                              │
               ▼                              ▼
┌──────────────────────────┐    ┌─────────────────────────────────┐
│    Eloquent Model        │    │        Session / Cache           │
│  Product::where(...)     │    │  session(['price' => $total])    │
│  Cart::create([...])     │    │  Session::get('app_locale')      │
│  Order::find($id)        │    └─────────────────────────────────┘
└──────────────┬───────────┘
               │
               ▼
┌──────────────────────────┐
│        MySQL DB          │
│  (users, products, cart, │
│   orders, bookings,      │
│   reviews, admins)       │
└──────────────┬───────────┘
               │
               ▼ (data trả về Controller)
┌─────────────────────────────────────────────────────────────────┐
│                Controller trả về                                 │
│  return view('products.menu', compact('drinks','desserts'))      │
│  hoặc return redirect()->route('products.cart')                  │
└─────────────────────────┬───────────────────────────────────────┘
                          │ (nếu là view)
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                   Blade Template Engine                          │
│  resources/views/products/menu.blade.php                        │
│  @extends('layouts.app') → layout chính                        │
│  @foreach($drinks as $drink) → render data                      │
│  {{ __('messages.menu_title') }} → dịch ngôn ngữ               │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                    HTTP Response                                  │
│  HTML được render → gửi về browser                              │
└─────────────────────────────────────────────────────────────────┘
```

---

## 8. NHỮNG LỖI NGƯỜI MỚI HAY GẶP

### Lỗi 1: CSRF Token Mismatch (419)
**Nguyên nhân:** Quên `@csrf` trong form POST.
```blade
{{-- SAI --}}
<form method="POST" action="/products/addcart">
    ...
</form>

{{-- ĐÚNG --}}
<form method="POST" action="/products/addcart">
    @csrf
    ...
</form>
```

### Lỗi 2: Mass Assignment Exception
**Nguyên nhân:** Cột không có trong `$fillable` mà dùng `create()`.
```php
// Lỗi nếu 'type' không có trong $fillable
Product::create(['name' => 'Latte', 'type' => 'Drinks']);

// Fix: thêm 'type' vào $fillable trong Product model
protected $fillable = ['name', 'image', 'price', 'description', 'type'];
```

### Lỗi 3: Nhầm Guard khi kiểm tra Auth
```php
// SAI: Admin đăng nhập nhưng kiểm tra web guard
if (Auth::check()) { ... }          // Sẽ false nếu dùng admin guard

// ĐÚNG:
if (Auth::guard('admin')->check()) { ... }
if (Auth::guard('web')->check()) { ... }
```

### Lỗi 4: Route không tìm thấy (404)
**Nguyên nhân hay gặp:**
- Sai method (GET vs POST)
- Sai tên route
- Middleware chặn (bị redirect thay vì 404)

```bash
# Kiểm tra danh sách route
php artisan route:list
```

### Lỗi 5: View không nhận được biến
```php
// SAI: compact() nhưng tên biến sai
$products = Product::all();
return view('products.menu', compact('product'));  // 'product' không tồn tại!

// ĐÚNG:
return view('products.menu', compact('products'));  // Đúng tên biến
```

### Lỗi 6: Redirect loop với Auth Middleware
**Nguyên nhân:** Route cần `auth:web` nhưng `/login` lại cũng redirect về route đó sau khi login xong.

```php
// config/auth.php
'redirects' => [
    'login' => '/home',   // Sau khi login redirect về /home, không phải route cũ
],
```

### Lỗi 7: N+1 Query Problem
```php
// SAI: Với mỗi order lại query DB thêm 1 lần
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->user->name;  // N queries thêm!
}

// Dự án này chưa có relationship → không gặp vấn đề này
// Nhưng cần biết nếu mở rộng sau này dùng: Order::with('user')->get()
```

### Lỗi 8: Session data mất sau redirect
**Nguyên nhân:** Dùng `session()` thay vì `with()` khi redirect.
```php
// SAI:
session(['success' => 'Done!']);
return redirect()->route('home');

// ĐÚNG (flash message - tự xóa sau 1 request):
return redirect()->route('home')->with('success', 'Done!');
```

---

## 9. CHECKLIST TỰ ĐÁNH GIÁ

Sau khi đọc xong dự án, bạn có thể tự kiểm tra bằng các câu hỏi sau:

### Level 1 — Cơ bản (phải biết)
- [ ] Dự án này làm gì? Có những chức năng chính nào?
- [ ] Có bao nhiêu bảng trong DB? Mỗi bảng lưu gì?
- [ ] Route nào cần đăng nhập? Route nào public?
- [ ] Controller nào xử lý trang Menu? Trang Cart?
- [ ] `$fillable` trong Model để làm gì?
- [ ] `@csrf` trong form để làm gì?
- [ ] Khác nhau giữa `{{ }}` và `{!! !!}` trong Blade?

### Level 2 — Hiểu luồng (nên biết)
- [ ] Giải thích luồng từ khi user click "Add to Cart" đến khi thấy giỏ hàng
- [ ] Middleware `auth:web` và `auth:admin` khác nhau thế nào?
- [ ] Tại sao Admin phải login riêng, không dùng chung bảng `users`?
- [ ] Session `price` được set ở đâu? Dùng để làm gì?
- [ ] File dịch ngôn ngữ nằm ở đâu? Cách thêm key mới?

### Level 3 — Phân tích sâu (tốt nếu biết)
- [ ] Tại sao `cart.user_id` là `varchar` thay vì `integer`?
- [ ] `booking.user_id` nullable — điều này ảnh hưởng gì đến logic?
- [ ] `Product::getLocalizedDescriptionAttribute()` hoạt động như thế nào?
- [ ] Nếu muốn thêm quan hệ `Order hasMany CartItems`, sẽ thêm vào đâu?
- [ ] Middleware `CheckForPrice` ngăn chặn điều gì? Tại sao cần thiết?

---

## 10. QUICK REFERENCE — LỆNH HAY DÙNG

```bash
# Chạy server local
php artisan serve

# Xem tất cả routes
php artisan route:list

# Chạy migration
php artisan migrate

# Reset DB và chạy lại tất cả + seed dữ liệu mẫu
php artisan migrate:fresh --seed

# Tạo Controller mới
php artisan make:controller TenController

# Tạo Model + Migration cùng lúc
php artisan make:model TenModel -m

# Tạo Middleware
php artisan make:middleware TenMiddleware

# Xem log lỗi
tail -f storage/logs/laravel.log
# Trên Windows: type storage\logs\laravel.log

# Clear cache khi config không nhận
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 11. TÓM TẮT CÁC FILE QUAN TRỌNG NHẤT

| Mục đích | File |
|---|---|
| Định nghĩa URL | `routes/web.php` |
| Cấu hình app khởi động | `bootstrap/app.php` |
| Cấu hình Auth guards | `config/auth.php` |
| Layout chính (user) | `resources/views/layouts/app.blade.php` |
| Layout admin | `resources/views/layouts/admin.blade.php` |
| Xử lý trang chủ | `app/Http/Controllers/HomeController.php` |
| Xử lý sản phẩm/cart/booking | `app/Http/Controllers/Products/ProductsController.php` |
| Xử lý user account | `app/Http/Controllers/Users/UsersController.php` |
| Toàn bộ trang Admin | `app/Http/Controllers/Admins/AdminsController.php` |
| Model sản phẩm | `app/Models/Product/Product.php` |
| Đặt ngôn ngữ | `app/Http/Middleware/SetLocale.php` |
| Chặn non-super-admin | `app/Http/Middleware/CheckSuperAdmin.php` |
| Dữ liệu mẫu | `database/seeders/` |
| File dịch tiếng Anh | `resources/lang/en/messages.php` |
| File dịch tiếng Nhật | `resources/lang/ja/messages.php` |
| Trang login admin | `resources/views/admins/login.blade.php` |
| Quản lý khách hàng | `resources/views/admins/allusers.blade.php` |
| Sửa khách hàng | `resources/views/admins/edituser.blade.php` |
| Migration thêm role/firebase | `database/migrations/2026_04_18_000001_add_role_and_firebase_uid_to_admins_table.php` |

---

---

## 12. THAY ĐỔI GẦN ĐÂY (CẬP NHẬT 2026-04)

### Hệ thống phân quyền Admin (Super Admin / Sub-Admin)

**Vấn đề cũ:** Tất cả admin có quyền ngang nhau, không có ai kiểm soát việc tạo/xóa tài khoản admin.

**Giải pháp mới:**
- Thêm cột `role` (`super_admin` / `admin`) và `firebase_uid` vào bảng `admins`
- **Super Admin** đăng nhập qua Google (Firebase Authentication) — không có password trong DB
- **Sub-Admin** đăng nhập email/password như cũ
- Middleware `CheckSuperAdmin` bảo vệ các route quản lý admin

**Files thay đổi:**
- `database/migrations/2026_04_18_000001_add_role_and_firebase_uid_to_admins_table.php` — thêm 2 cột mới
- `app/Models/Admin/Admin.php` — thêm `role`, `firebase_uid` vào `$fillable`, thêm `isSuperAdmin()`
- `app/Http/Middleware/CheckSuperAdmin.php` — middleware mới
- `bootstrap/app.php` — đăng ký alias `'super.admin'`
- `routes/web.php` — route `firebase-login`, nhóm `super.admin` middleware
- `app/Http/Controllers/Admins/AdminsController.php` — thêm `firebaseLogin()`, `deleteAdmins()`
- `resources/views/admins/login.blade.php` — thêm nút Google Sign-In
- `resources/views/admins/alladmins.blade.php` — badge role, nút Xóa
- `config/services.php` — thêm `firebase.api_key`, `firebase.super_admin_email`
- `.env` — thêm `FIREBASE_API_KEY`, `FIREBASE_SUPER_ADMIN_EMAIL`

### Quản lý khách hàng (Customer Management)

**Tính năng mới:** Admin có thể xem, sửa tên/email, xóa tài khoản khách hàng trực tiếp từ trang admin.

**Files mới:**
- `resources/views/admins/allusers.blade.php` — bảng danh sách user, phân trang 15/trang
- `resources/views/admins/edituser.blade.php` — form sửa name/email
- `app/Http/Controllers/Admins/AdminsController.php` — thêm `displayUsers()`, `editUser()`, `updateUser()`, `deleteUser()`
- `routes/web.php` — 4 routes mới: `all.users`, `edit.user`, `update.user`, `delete.user`
- `resources/views/layouts/admin.blade.php` — thêm link "Customers" vào navbar

---

*Tài liệu này được tạo dựa trên source code thực tế của dự án CoffeeBlend Laravel tại `c:\xampp\htdocs\coffeeblend_laravel`.*
