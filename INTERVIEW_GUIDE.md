# CoffeeBlend Laravel - Hướng Dẫn Phỏng Vấn Kỹ Thuật

> Tài liệu này được viết theo góc nhìn của Senior Engineer / Technical Interviewer Nhật Bản.
> Mục tiêu: Giúp bạn hiểu sâu dự án để trả lời phỏng vấn tự tin, trôi chảy.

---

## Tóm Tắt Nhanh (Quick Summary)

> Dùng phần này để ôn lại nhanh trước buổi phỏng vấn hoặc khi cần trình bày ngắn gọn.

### Tổng quan dự án
- Hệ thống **E-Commerce + Booking** cho quán cà phê, phục vụ 2 nhóm: Customer và Admin
- Kiến trúc **Monolithic Web App** — frontend và backend tích hợp trong 1 codebase
- Mô hình **MVC** với Server-Side Rendering, không phải SPA hay API-only
- Hỗ trợ **đa ngôn ngữ** Tiếng Anh và Tiếng Nhật (i18n)
- Có trang **Admin Dashboard** riêng biệt để quản lý sản phẩm, đơn hàng, đặt bàn

### Công nghệ sử dụng
- **Laravel 12 / PHP 8.2** — framework chính xử lý routing, auth, ORM, middleware
- **SQLite** — database đơn giản, zero-config, phù hợp môi trường development
- **Blade Template Engine** — render HTML phía server, layout inheritance với `@extends/@section`
- **Bootstrap 5 + Colorlib Template** — xây dựng UI responsive, compile qua **Vite**
- **Docker** — đóng gói toàn bộ app (PHP, Apache, frontend build) vào 1 container

### Môi trường phát triển
- Local: **XAMPP** + `php artisan serve`, frontend dùng `npm run dev` (Vite HMR)
- Database quản lý qua **Migration files** — version control cho schema
- **Seeders** riêng cho từng model → reset và tạo test data nhanh chóng
- Docker startup script tự động chạy `migrate`, `config:cache`, `route:cache` khi container khởi động
- Environment config tách biệt qua `.env` — dễ switch giữa dev/production

### Các tính năng triển khai chính
- **Dual Authentication**: 2 guard riêng (`web` cho user, `admin` cho admin) — bảo mật tuyệt đối
- **Guest Cart Flow**: Lưu pending cart vào session → restore tự động sau khi login
- **Checkout Guard**: Middleware `CheckForPrice` chặn truy cập trực tiếp vào `/checkout`
- **Multi-Language (EN/JA)**: Session-based locale switching, product có field `description_ja` riêng
- **Admin CRUD**: Quản lý đầy đủ sản phẩm, đơn hàng, đặt bàn, tài khoản admin

### Kết quả và bài học kinh nghiệm
- Hiểu sâu về **MVC pattern**, middleware pipeline, và dual guard authentication trong Laravel
- Nhận ra giới hạn thiết kế: **Cart thiếu cột quantity**, Review không liên kết User, Admin thiếu password hashing
- Học được tầm quan trọng của **Migration versioning** — thêm cột `description_ja` mà không mất data
- Nhận thức được sự đánh đổi: SQLite tiện cho dev nhưng cần MySQL/PostgreSQL cho production thực tế
- Hiểu rằng **automated testing** là điểm cần cải thiện — nên viết Feature Tests cho các luồng auth, cart, checkout

---

## 1. Tổng Quan Dự Án

### Đây là loại hệ thống gì?

**CoffeeBlend** là một hệ thống **E-Commerce + Booking** cho quán cà phê, bao gồm:

- **Người dùng thông thường (Customer)**: Xem menu, thêm giỏ hàng, đặt hàng online, đặt bàn, viết đánh giá.
- **Quản trị viên (Admin)**: Quản lý sản phẩm, đơn hàng, đặt bàn, tài khoản admin.
- **Đa ngôn ngữ**: Hỗ trợ Tiếng Anh (EN) và Tiếng Nhật (JA).

### Kiến trúc tổng thể

```
┌─────────────────────────────────────────────┐
│              Monolithic Web App              │
│                                             │
│  ┌──────────────┐    ┌──────────────────┐  │
│  │   Frontend   │    │    Backend       │  │
│  │ Blade Views  │◄──►│  Laravel MVC     │  │
│  │ Bootstrap    │    │  Controllers     │  │
│  │ Custom CSS   │    │  Models          │  │
│  └──────────────┘    │  Middleware      │  │
│                      └────────┬─────────┘  │
│                               │             │
│                      ┌────────▼─────────┐  │
│                      │    Database      │  │
│                      │    SQLite        │  │
│                      └──────────────────┘  │
└─────────────────────────────────────────────┘
```

**Đây là Monolithic Application** - Frontend và Backend tích hợp trong cùng 1 codebase, KHÔNG phải SPA hay API-only.

### Mô hình thiết kế

| Mô hình | Áp dụng trong dự án |
|---------|---------------------|
| **MVC** | Laravel MVC: Model (Eloquent) + View (Blade) + Controller |
| **Server-Side Rendering** | Blade template engine render HTML phía server |
| **Session-Based Auth** | Dùng Laravel session để xác thực (không phải JWT) |
| **RESTful-like Routing** | Đặt tên route theo convention nhưng không hoàn toàn RESTful |

---

## 2. Phân Tích Công Nghệ Sử Dụng

### 2.1 Laravel 12 (PHP Framework)

**Dùng để làm gì?**
- Framework chính xây dựng toàn bộ backend: routing, auth, database, middleware, templating.

**Vì sao chọn Laravel?**
- Convention-over-configuration: Ít config, nhiều tính năng có sẵn (Auth, ORM, Migration).
- Ecosystem phong phú: Tinker, Sail, Pint, Queue, v.v.
- Cộng đồng lớn, tài liệu tốt → phù hợp với team nhỏ.

**Giải quyết vấn đề gì?**
- Xây dựng nhanh web app có CRUD, Auth, Validation mà không phải viết boilerplate.

**Alternative?**
- Symfony (phức tạp hơn, enterprise-grade)
- CodeIgniter (nhẹ hơn nhưng ít tính năng)
- Node.js/Express (nếu muốn JS fullstack)

**Câu trả lời phỏng vấn "Tại sao Laravel thay vì Symfony?":**
> "Laravel phù hợp hơn cho dự án tầm trung như CoffeeBlend vì nó có tốc độ phát triển nhanh với nhiều tính năng built-in như authentication scaffolding, Eloquent ORM, và Blade template. Symfony mạnh về enterprise nhưng có learning curve cao hơn. Với timeframe và scope của dự án này, Laravel là lựa chọn pragmatic."

---

### 2.2 SQLite (Database)

**Dùng để làm gì?**
- Lưu trữ toàn bộ dữ liệu: users, products, orders, bookings, reviews.

**Vì sao chọn SQLite thay vì MySQL?**
- Development environment: Zero config, không cần cài database server riêng.
- SQLite lưu trong 1 file `database/database.sqlite` → dễ copy, backup, test.
- Laravel hỗ trợ tốt SQLite.

**Nhược điểm SQLite trong production:**
- Không hỗ trợ concurrent write tốt (chỉ 1 writer tại 1 thời điểm).
- Không phù hợp khi traffic cao hoặc nhiều server (distributed).

**Câu trả lời phỏng vấn "Nếu deploy production, bạn có giữ SQLite không?":**
> "Không. SQLite phù hợp cho development và staging vì đơn giản và portable. Cho production, tôi sẽ migrate sang MySQL hoặc PostgreSQL vì chúng hỗ trợ concurrent connections tốt hơn, có thể scale, và có nhiều công cụ monitoring hơn. Laravel cho phép đổi DB connection chỉ bằng thay đổi file .env."

---

### 2.3 Blade Template Engine

**Dùng để làm gì?**
- Render HTML phía server, kết hợp PHP logic với HTML layout.

**Tính năng dùng trong dự án:**
- `@extends`, `@section`, `@yield` → layout inheritance
- `@include` → component inclusion (ví dụ: `home/index.blade.php` include `_hero`, `_menu`, `_about`)
- `@auth`, `@guest` → conditional rendering based on auth state
- `{{ }}` để output data, `{!! !!}` cho HTML unescaped
- `@foreach`, `@if`, `@csrf`, `@method` directives

**Alternative?**
- Twig (dùng trong Symfony)
- Inertia.js + Vue/React (nếu muốn SPA feel với SSR)

---

### 2.4 Bootstrap 5.2.3

**Dùng để làm gì?**
- CSS framework để xây dựng UI responsive nhanh.

**Trong dự án:**
- Bootstrap được install qua npm, compile bởi Vite.
- Dùng Bootstrap Grid, utilities, form components.
- Template chính là Colorlib (custom CSS 12,000+ dòng) override một số Bootstrap styles.

**Alternative?**
- Tailwind CSS (đã có trong package.json nhưng chưa dùng nhiều)
- Foundation, Bulma

---

### 2.5 Vite (Build Tool)

**Dùng để làm gì?**
- Bundle JavaScript, compile SCSS → CSS, output file tối ưu cho production.

**Vì sao Vite thay vì Webpack?**
- Vite nhanh hơn Webpack nhiều trong development (HMR - Hot Module Replacement tức thì).
- Laravel đã switch sang Vite từ v9 (từ bỏ Mix/Webpack).
- Config đơn giản hơn.

**Trong dự án:**
```js
// vite.config.js
laravel({
    input: ['resources/sass/app.scss', 'resources/js/app.js'],
    refresh: true,
})
```

**Câu trả lời phỏng vấn "Bạn hiểu build process không?":**
> "Khi chạy `npm run dev`, Vite watch file changes và reload browser tự động. Khi `npm run build`, Vite bundle và minify JS/CSS vào `public/build/`, Blade dùng `@vite()` directive để link đúng file đã hash. Hash giúp browser cache bust khi file thay đổi."

---

### 2.6 Localization (Đa Ngôn Ngữ)

**Implement như thế nào?**
1. `SetLocale` middleware đọc `session('app_locale')` → set `App::setLocale()`.
2. User click `EN/JP` → `GET /lang/{locale}` → `LanguageController@switch()` lưu vào session.
3. Views dùng `__('messages.key')` → Laravel tìm trong `resources/lang/{locale}/messages.php`.
4. Product model có method `getLocalizedDescription()` → trả về `description_ja` hoặc `description`.

**Điểm thú vị:**
- Japanese font (Noto Sans JP) load qua Google Fonts, chỉ apply khi `body[data-lang="ja"]`.
- Product table có cột `description_ja` riêng (không phải bảng translation riêng).

---

### 2.7 Docker

**Dockerfile trong dự án:**
- Base image: `php:8.2-apache`
- Cài extensions: GD (image), ZIP, PDO MySQL, BCMath, v.v.
- Multi-stage không hoàn toàn: Cài Node.js trong cùng image để build frontend.
- Apache DocumentRoot set thành `/var/www/html/public`.

**Startup script** (`docker/start.sh`):
```bash
php artisan key:generate --force  # nếu chưa có APP_KEY
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force       # nếu SEED_DB=true
php artisan storage:link
apache2-foreground
```

---

## 3. Phân Tích Backend

### 3.1 Cấu Trúc Thư Mục Nói Lên Điều Gì?

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admins/       ← namespace grouping cho admin
│   │   ├── Auth/         ← tách biệt auth controllers
│   │   ├── Products/     ← domain-based grouping
│   │   └── Users/        ← domain-based grouping
│   └── Middleware/
│       ├── CheckForAuth.php   ← bảo vệ admin login page
│       ├── CheckForPrice.php  ← bảo vệ checkout flow
│       └── SetLocale.php      ← i18n middleware
├── Models/
│   ├── Admin/
│   │   └── Admin.php     ← tách Admin khỏi User
│   └── Product/
│       ├── Booking.php
│       ├── Cart.php
│       ├── Order.php
│       ├── Product.php
│       └── Review.php
```

**Tư duy thiết kế:**
- **Domain-based grouping** thay vì flat structure → dễ navigate khi project lớn.
- Tách `Admin` model khỏi `User` → bảo mật tốt hơn (2 bảng riêng, 2 guards riêng).
- Custom Middleware để bảo vệ từng luồng cụ thể.

---

### 3.2 Cách Xử Lý Routing

**File**: `routes/web.php`

**3 nhóm route chính:**

```php
// 1. Public routes - không cần đăng nhập
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// 2. User routes - cần đăng nhập (guard: web)
Route::middleware('auth:web')->group(function () {
    Route::get('/users/orders', [UsersController::class, 'displayOrders'])->name('users.orders');
    // ...
});

// 3. Admin routes - cần đăng nhập (guard: admin)
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/index', [AdminsController::class, 'index'])->name('admin.index');
    // ...
});
```

**Điểm đáng nói:**
- `check.for.price` middleware bảo vệ checkout: nếu `session('price') == 0`, redirect về giỏ hàng → tránh user trực tiếp vào `/checkout` mà không có item.
- Named routes (`->name('...')`) được dùng nhất quán → dễ generate URL bằng `route('name')`.

---

### 3.3 Authentication Implement Như Thế Nào?

**Dual Guard System:**

```php
// config/auth.php
'guards' => [
    'web'   => ['driver' => 'session', 'provider' => 'users'],
    'admin' => ['driver' => 'session', 'provider' => 'admins'],
],
'providers' => [
    'users'  => ['driver' => 'eloquent', 'model' => App\Models\User::class],
    'admins' => ['driver' => 'eloquent', 'model' => App\Models\Admin\Admin::class],
],
```

**Luồng User Authentication:**
1. User submit form `/login` → `LoginController@login()` (Laravel UI boilerplate).
2. Laravel xác thực bằng `auth('web')` guard, check `users` table.
3. Session được tạo, user redirect về trang trước đó (hoặc home).
4. **Bonus feature**: Sau login, `authenticated()` callback kiểm tra `session('pending_cart')` và tạo cart items.

**Luồng Admin Authentication:**
1. Admin submit form `/admin/login` → `AdminsController@checkLogin()`.
2. Dùng `Auth::guard('admin')->attempt([...])` để xác thực.
3. Middleware `check.for.aut` chặn admin đã login truy cập trang login lại.

**Điểm cần giải thích được:**
- `auth:web` và `auth:admin` là 2 guard riêng biệt → User bình thường không thể vào admin.
- Session driver: `database` (lưu trong bảng `sessions`).

---

### 3.4 Database Design Đặc Điểm Gì?

**Schema tổng quát:**

```
users          admins
  │              │
  │              │
  ▼              ▼
orders        (riêng biệt)
bookings
  │
  ▼
products
  │
  ▼
cart (per user)
  │
  └── pro_id → products.id (không có foreign key constraint)

reviews (standalone, không liên kết user)
```

**Điểm mạnh:**
- Migration files có timestamp → dễ track history thay đổi schema.
- Seeders riêng cho từng model → dễ test và reset data.
- Model `Product` có `description_ja` riêng → đơn giản hơn bảng translation riêng.

**Điểm có thể cải thiện (Hãy chuẩn bị trả lời thành thật):**
1. **Cart table thiếu quantity**: Mỗi row = 1 item, nếu muốn số lượng = nhiều row → không tối ưu.
2. **Không có foreign key constraints**: `cart.pro_id` tham chiếu `products.id` nhưng không có `FOREIGN KEY` → không có referential integrity.
3. **Review không liên kết User**: Ai cũng có thể review, không track được đã mua chưa.
4. **Admin.php không hash password**: User model dùng `casts: ['password' => 'hashed']` nhưng Admin model không có → tiềm ẩn bảo mật.
5. **SQLite cho production**: Như đã đề cập, không phù hợp high-traffic.

---

### 3.5 Có Thể Cải Thiện Gì?

```
Hiện tại:                    Cải thiện đề xuất:
─────────────────────────    ─────────────────────────────
Cart không có quantity   →   Thêm cột quantity, dùng updateOrCreate
Không có API layer       →   Thêm Laravel Sanctum cho mobile app
Không có tests           →   Thêm Feature Tests cho auth, cart, checkout
Admin CRUD thủ công      →   Dùng Laravel Nova hoặc Filament
SQLite production        →   MySQL/PostgreSQL
Không có queue           →   Thêm queue cho email notification
```

---

## 4. Phân Tích Frontend

### 4.1 Cách Tổ Chức Component

Dự án dùng **Blade Components (Include Style)**:

```
resources/views/
├── layouts/
│   ├── app.blade.php       ← Layout chính (header, footer, @yield)
│   └── admin.blade.php     ← Layout admin riêng
├── home/
│   ├── index.blade.php     ← Main page, chỉ gọi @include
│   ├── _hero.blade.php     ← Component: Hero slider
│   ├── _menu.blade.php     ← Component: Menu section
│   ├── _about.blade.php    ← Component: About section
│   ├── _services.blade.php ← Component: Services
│   ├── _bestsellers.blade.php ← Component: Best sellers
│   ├── _gallery.blade.php  ← Component: Gallery
│   ├── _testimony.blade.php ← Component: Testimonials
│   └── _alerts.blade.php   ← Component: Flash messages
└── partials/
    ├── _info_bar.blade.php
    └── _scroll_hint.blade.php
```

**Convention dùng `_` prefix** để phân biệt partial/component với full page.

Cách include:
```blade
{{-- home/index.blade.php --}}
@extends('layouts.app')
@section('content')
    @include('home._hero')
    @include('home._menu')
    @include('home._bestsellers', ['products' => $products])
    @include('home._testimony', ['reviews' => $reviews])
@endsection
```

---

### 4.2 State Management

**Không có client-side state management** (không React, không Vue). State được quản lý:

| State | Cách lưu |
|-------|----------|
| Authentication | PHP Session (server-side) |
| Language preference | PHP Session (`session('app_locale')`) |
| Cart total cho checkout | PHP Session (`session('price')`) |
| Pending cart (guest) | PHP Session (`session('pending_cart')`) |
| Flash messages | Laravel `session()->flash()` |

---

### 4.3 Xử Lý API Call

Dự án **không dùng AJAX cho core features**. Hầu hết interaction là form submit và page reload.

**Ngoại lệ**: Firebase được install (`firebase: ^12.9.0` trong package.json) nhưng có thể chưa implement đầy đủ hoặc dùng cho notification.

**Form submission pattern:**
```blade
<form action="{{ route('users.orders') }}" method="POST">
    @csrf
    @method('PUT')  {{-- Blade method spoofing cho PUT/DELETE --}}
    ...
</form>
```

---

### 4.4 Validation Phía Client

Dự án chủ yếu dùng **server-side validation** qua Laravel:

```php
// Trong controller
$validated = $request->validate([
    'name'  => 'required|string|max:255',
    'date'  => 'required|date|after:today',
    'phone' => 'required',
]);
```

Blade hiển thị lỗi:
```blade
@error('name')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
```

**Client-side validation**: HTML5 `required`, `type="email"`, `type="date"` attributes.

---

### 4.5 UX Đáng Nói

1. **Guest → Login → Cart Flow**: User thêm vào giỏ hàng mà chưa login → session lưu pending cart → sau login tự động restore → trải nghiệm mượt.
2. **Flash Messages**: Sau mỗi action (booking, order, review) đều có alert feedback.
3. **Language Switcher**: Không reload nội dung bất đồng bộ mà redirect + flash message.
4. **Checkout Guard**: Middleware `CheckForPrice` ngăn user trực tiếp vào `/checkout` → UX nhất quán.

---

## 5. DevOps / Environment

### 5.1 Docker Được Dùng Thế Nào?

**Dockerfile** (single-stage, php:8.2-apache base):
```
php:8.2-apache
├── System packages (git, unzip, libpng, ...)
├── PHP extensions (GD, ZIP, PDO, BCMath, ...)
├── Node.js 20 (để build frontend)
├── Composer
├── Copy code
├── composer install --no-dev
├── npm install && npm run build
└── Apache config (DocumentRoot = /public)
```

**Startup script** (`docker/start.sh`) chạy khi container start:
```bash
php artisan key:generate --force
php artisan config:cache    # cache config cho performance
php artisan route:cache     # cache routes
php artisan view:cache      # cache compiled Blade views
php artisan migrate --force # auto-migrate
apache2-foreground          # start web server
```

### 5.2 Cách Deploy Hoạt Động

**Development (local):**
```bash
php artisan serve  # hoặc XAMPP/Laragon
npm run dev        # Vite dev server với HMR
```

**Docker deployment:**
```bash
docker build -t coffeeblend .
docker run -p 8080:80 \
  -e APP_KEY=... \
  -e APP_ENV=production \
  -e SEED_DB=true \
  coffeeblend
```

**Tất cả trong 1 container**: PHP, Apache, và đã build frontend. Đơn giản nhưng không scalable.

### 5.3 Nếu Hỏi Về CI/CD

**Thực tế dự án chưa có CI/CD pipeline**. Câu trả lời trung thực nhưng thể hiện tư duy:

> "Dự án hiện chạy trên Docker thuận tiện cho deployment thủ công. Nếu áp dụng CI/CD, tôi sẽ setup GitHub Actions: khi push lên `main`, tự động chạy `php artisan test`, build Docker image, push lên Container Registry (Docker Hub hoặc AWS ECR), rồi deploy lên server. Điều này đảm bảo không deploy code bị broken và tự động hóa quy trình."

**Pipeline mẫu bạn có thể đề xuất:**
```
Push to main
    → GitHub Actions trigger
    → Run: composer install
    → Run: php artisan test
    → Run: npm run build
    → Build Docker image
    → Push to registry
    → Deploy to server (SSH / k8s / ECS)
```

---

## 6. Điểm Mạnh Kỹ Thuật Của Dự Án

Đây là những điều bạn có thể **tự tin nói trong phỏng vấn**:

### 6.1 Dual Authentication System
> "Tôi implement 2 guard authentication riêng biệt: `web` cho user và `admin` cho admin. Điều này đảm bảo user bình thường tuyệt đối không thể access admin panel, dù họ biết URL."

### 6.2 Guest-to-User Cart Preservation
> "Tôi xử lý case UX phức tạp: user thêm sản phẩm vào giỏ hàng khi chưa login. Thay vì mất giỏ hàng khi redirect về login, tôi lưu pending cart vào session và restore sau khi login thành công trong callback `authenticated()`."

### 6.3 Multi-Language Support (EN/JA)
> "Dự án có hỗ trợ đa ngôn ngữ Anh-Nhật dùng Laravel's built-in localization system với session-based locale switching. Product descriptions có field riêng cho từng ngôn ngữ với fallback logic trong model."

### 6.4 Middleware-based Access Control
> "Tôi thiết kế custom middleware `CheckForPrice` để bảo vệ checkout flow, ngăn user bypass đến trang payment mà không đi qua cart. `CheckForAuth` redirect admin đã login ra khỏi login page. Đây là separation of concerns: business logic trong middleware, không phải trong controller."

### 6.5 Database Migration & Seeding
> "Toàn bộ schema được quản lý qua migration files với versioning rõ ràng. Seeders cho phép reset môi trường test nhanh chóng. Điều này cũng giúp Docker deployment tự động migrate và seed khi container start."

### 6.6 Modular Blade Component Structure
> "Views được tổ chức theo component pattern với `_` prefix convention. Home page chia thành 8 partial components riêng biệt, dễ maintain và reuse."

---

## 7. Điểm Yếu / Có Thể Bị Hỏi Xoáy

### 7.1 Câu Hỏi Khó Và Cách Trả Lời

---

**Q: "Cart table của bạn không có cột quantity. Nếu user muốn order 3 ly cà phê, bạn xử lý thế nào?"**

Thực tế: Hiện tại 3 row riêng biệt được tạo.

> Câu trả lời tốt: "Đây là điểm tôi nhận ra sau khi implement xong. Thiết kế hiện tại thêm nhiều rows cho cùng sản phẩm. Cách đúng là thêm cột `quantity` và dùng `updateOrCreate` để tăng quantity nếu sản phẩm đã trong giỏ. Tôi đã note điều này để cải thiện trong version tiếp theo."

---

**Q: "Tại sao bạn dùng SQLite thay vì MySQL? SQLite có phù hợp production không?"**

> "SQLite phù hợp cho development vì zero-config và portable. Cho production, tôi biết SQLite không phù hợp khi có concurrent writes nhiều. Dự án này đang ở giai đoạn development/demo, nếu go-live thật sự, tôi sẽ đổi sang MySQL bằng cách chỉ thay `DB_CONNECTION` trong `.env`."

---

**Q: "Review không có user_id. Ai cũng có thể review dù chưa mua hàng. Bạn nghĩ sao?"**

> "Đúng, đây là business logic gap. Lý tưởng, review nên link với Order và chỉ cho phép review sau khi đơn hàng completed. Cách implement: thêm `user_id` và `order_id` vào bảng reviews, check trong controller xem user có completed order không trước khi cho review. Điều này cũng giúp chống spam review."

---

**Q: "Admin model không hash password. Đây có phải security issue không?"**

> "Đúng, đây là bug. User model có `'password' => 'hashed'` trong casts nên Laravel tự hash khi assign. Admin model thiếu điều này. Nếu admin password được lưu plain text, đây là critical security vulnerability. Fix là thêm cast tương tự vào Admin model, hoặc override `setPasswordAttribute` để hash."

---

**Q: "Dự án không có tests. Làm sao bạn đảm bảo code chạy đúng?"**

> "Thành thật mà nói, tôi test thủ công trong quá trình development. Đây là điểm yếu tôi nhận ra. Nếu làm lại, tôi sẽ viết Feature Tests cho các luồng quan trọng: đăng ký/đăng nhập, thêm giỏ hàng, checkout. Laravel có PHPUnit tích hợp sẵn và `RefreshDatabase` trait để reset DB sau mỗi test. Tôi hiểu tầm quan trọng của testing, nhất là trong môi trường team."

---

**Q: "Bạn có biết difference giữa `@include` và Blade Components không? Tại sao bạn chọn `@include`?"**

> "Blade Components (dùng `<x-component-name>`) là approach mới hơn từ Laravel 7+, có slot, attribute forwarding, và class-based component. `@include` đơn giản hơn, truyền dữ liệu qua array. Tôi dùng `@include` vì nó straightforward cho partial views không cần complex logic. Nếu component phức tạp hơn hoặc cần tái sử dụng nhiều nơi với interface rõ ràng, tôi sẽ dùng Blade Components."

---

**Q: "Tại sao bạn không dùng Repository Pattern?"**

> "Dự án này có scope vừa phải và là solo project. Repository Pattern tốt cho large-scale app với team vì nó abstract database logic, dễ swap implementation, và dễ mock trong tests. Với dự án này, tôi chọn simple MVC trực tiếp để nhanh hơn. Nếu scale lên hoặc cần viết unit tests tốt, tôi sẽ thêm Repository Pattern."

---

**Q: "Firebase trong package.json nhưng tôi không thấy dùng rõ ràng. Sao vậy?"**

> "Firebase được install với ý định dùng cho real-time notification (ví dụ: admin nhận notification khi có đơn hàng mới). Hiện tại tính năng đang trong quá trình phát triển/planning. Đây là điểm trung thực tôi có thể nói rõ."

---

## 8. Câu Hỏi Phỏng Vấn Và Câu Trả Lời Mẫu

### 8.1 10 Câu Hỏi Kỹ Thuật Cơ Bản

---

**Q1: "Hãy giải thích MVC pattern trong dự án Laravel của bạn với ví dụ cụ thể."**

> "MVC trong CoffeeBlend: Khi user vào trang menu, request đến `ProductsController@menu()` (**Controller**). Controller dùng `Product::where('type', 'drink')->get()` để lấy dữ liệu từ **Model** Eloquent. Data được pass vào `products/menu.blade.php` (**View**) để render HTML. Controller là trung gian: nó không biết DB query details (đó là việc của Model) và không biết HTML structure (đó là View). Separation of concerns này giúp code dễ maintain."

---

**Q2: "Bạn implement authentication thế nào? Có điểm gì đặc biệt?"**

> "Dự án có 2 authentication guards riêng: `web` cho user và `admin` cho admin, mỗi guard dùng bảng DB và model khác nhau. Điều đặc biệt là tôi override callback `authenticated()` trong LoginController để restore pending cart items - những items user đã add trước khi login. Middleware `auth:web` và `auth:admin` protect các route tương ứng."

---

**Q3: "Middleware là gì? Bạn có custom middleware nào không?"**

> "Middleware là layer xử lý HTTP request trước hoặc sau khi vào controller. Tôi có 3 custom middleware: `SetLocale` apply ngôn ngữ từ session cho mọi request; `CheckForPrice` block access vào checkout nếu session price = 0, tránh user vào thẳng `/checkout`; `CheckForAuth` redirect admin đã login ra khỏi trang login admin. Đây là ví dụ về Single Responsibility: mỗi middleware làm 1 việc cụ thể."

---

**Q4: "Bạn xử lý validation thế nào?"**

> "Server-side validation dùng Laravel's `$request->validate()` với rules như `required`, `date|after:today`, `min:6`. Khi fail, Laravel tự redirect về form với errors trong `$errors` variable mà Blade hiển thị bằng `@error` directive. Phía client, dùng HTML5 attributes như `required`, `type='email'`. Server validation là source of truth vì client-side có thể bypass."

---

**Q5: "Eloquent ORM là gì? Tại sao dùng thay vì raw SQL?"**

> "Eloquent là Laravel's Active Record ORM. Thay vì viết `SELECT * FROM products WHERE type = 'drink'`, tôi viết `Product::where('type', 'drink')->get()`. Eloquent tự prevent SQL injection qua prepared statements, code dễ đọc hơn, relationships dễ define (`hasMany`, `belongsTo`). Đánh đổi là có thể slow hơn raw SQL cho complex queries, nhưng với dự án này performance đủ dùng."

---

**Q6: "CSRF protection là gì? Bạn có dùng không?"**

> "CSRF (Cross-Site Request Forgery) là attack giả mạo request từ browser của user. Laravel protect bằng cách generate CSRF token unique cho mỗi session. Mọi form POST trong dự án đều có `@csrf` directive để include token hidden field. Laravel middleware `VerifyCsrfToken` verify token trước khi xử lý request. Không có token hoặc token sai → 419 error."

---

**Q7: "Session và Cookie khác nhau thế nào? Dự án dùng cái nào?"**

> "Cookie lưu trên browser (client-side), Session lưu trên server và chỉ gửi session ID qua cookie. Dự án dùng session (`session('app_locale')`, `session('price')`) và session driver là `database` (lưu trong bảng `sessions`). Session an toàn hơn vì data thật không expose ra client, chỉ session ID trong cookie."

---

**Q8: "Hãy giải thích Blade @extends và @section."**

> "`@extends('layouts.app')` khai báo view này dùng layout `app.blade.php` làm skeleton. Layout có `@yield('content')` placeholder. Child view định nghĩa `@section('content') ... @endsection` để điền vào placeholder đó. Giống như template inheritance trong OOP: layout là parent class với hooks, child override các hooks. Điều này tránh duplicate header/footer code."

---

**Q9: "Tại sao bạn dùng `route()` helper thay vì hardcode URL?"**

> "Named routes như `route('users.orders')` generate URL từ route definition. Nếu URL thay đổi từ `/users/orders` sang `/account/orders`, tôi chỉ đổi ở `routes/web.php`, toàn bộ views và controllers dùng `route()` tự động update. Hardcode `/users/orders` khắp nơi sẽ phải tìm và sửa tất cả. Đây là Don't Repeat Yourself (DRY) principle."

---

**Q10: "Migration là gì? Tại sao không viết SQL trực tiếp?"**

> "Migration là version control cho database schema. Thay vì chạy SQL thủ công và không track, migration files có `up()` (apply change) và `down()` (rollback). Team member chạy `php artisan migrate` để sync schema. Docker startup cũng chạy auto. Quan trọng: tôi có migration `2026_02_28_..._add_description_ja_to_products_table.php` để thêm cột cho Japanese support mà không drop table hay mất data."

---

### 8.2 10 Câu Hỏi Follow-up Sâu Hơn

---

**Q11: "Nếu site có 10,000 concurrent users, bạn sẽ scale thế nào?"**

> "Hiện tại dự án là single Docker container. Để scale: 1) Đổi SQLite → MySQL/PostgreSQL với connection pooling. 2) Thêm Redis cho session và cache thay vì database. 3) Horizontal scaling: nhiều container PHP-FPM + Nginx load balancer. 4) CDN cho static assets (CSS, JS, images). 5) Queue worker riêng cho emails. 6) Read replicas cho database. Bước đầu tôi sẽ profile xem bottleneck ở đâu trước khi optimize."

---

**Q12: "Bạn xử lý race condition trong checkout thế nào? Ví dụ 2 user cùng mua item cuối cùng."**

> "Hiện tại chưa handle. Dự án cũng không có stock/inventory tracking. Nếu có, tôi sẽ dùng database transaction với `DB::transaction()` và pessimistic locking (`lockForUpdate()`) khi check và decrement stock. Hoặc dùng queue để serialize checkout requests. Đây là concurrency issue quan trọng trong e-commerce thực tế."

---

**Q13: "Tại sao bạn chọn session-based auth thay vì JWT?"**

> "Session-based phù hợp với web app server-rendered như dự án này. JWT tốt hơn cho stateless API hoặc mobile app vì không cần server store session state, dễ horizontal scale. Dự án CoffeeBlend là Blade/SSR, user interact qua browser, session-based đơn giản và đủ. Nếu tôi thêm mobile app, tôi sẽ thêm API routes với Laravel Sanctum (token-based) song song."

---

**Q14: "Explain N+1 query problem và Laravel giải quyết thế nào?"**

> "N+1 problem: lấy 10 orders rồi loop, mỗi order query thêm user info → 1 + 10 = 11 queries. Laravel giải quyết bằng Eager Loading: `Order::with('user')->get()` → 2 queries total (1 cho orders, 1 JOIN users). Trong dự án, tôi có thể check bằng Laravel Debugbar để detect N+1. Ví dụ: nếu hiển thị orders với user name, tôi dùng `Order::with('user')->where('user_id', auth()->id())->get()`."

---

**Q15: "Làm thế nào để protect dự án khỏi SQL injection?"**

> "Laravel Eloquent và Query Builder dùng PDO prepared statements, tự động escape user input. `Product::where('type', $request->type)` → safe. Nguy hiểm là dùng raw SQL với string interpolation: `DB::select('SELECT * FROM products WHERE type = $type')` → injectable. Trong dự án tôi không dùng raw SQL, validation `$request->validate()` thêm một lớp filter nữa."

---

**Q16: "Bạn hiểu Laravel service container và dependency injection không?"**

> "Service container là IoC (Inversion of Control) container của Laravel. Khi controller cần `Request $request` trong constructor hoặc method, Laravel tự inject instance đúng type. `app()->make(ProductService::class)` hoặc type-hint trong constructor → container resolve dependencies tự động. Trong dự án tôi dùng implicitly qua controller method injection. Để custom service, tôi đăng ký trong `AppServiceProvider`."

---

**Q17: "Tại sao Blade dùng `{{ }}` và `{!! !!}` khác nhau?"**

> "`{{ $var }}` auto-escape HTML entities, chuyển `<script>` thành `&lt;script&gt;` → XSS safe. `{!! $var !!}` output raw HTML, không escape → dùng khi cần render HTML đã sanitize. Ví dụ: `{{ $user->name }}` an toàn. `{!! $product->htmlDescription !!}` chỉ dùng khi bạn trust data. Trong dự án tôi chỉ dùng `{{ }}` cho user-generated content."

---

**Q18: "Git workflow của bạn thế nào? Bạn có dùng branching strategy không?"**

> "Nhìn vào git log, tôi làm việc trên branch `main` với commits có message rõ ràng theo tính năng. Nếu làm việc team, tôi sẽ dùng GitHub Flow: `main` luôn deployable, feature branches từ main, PR với code review trước khi merge. Commit messages bằng tiếng Việt vì dự án cá nhân, nhưng trong team Nhật tôi sẽ dùng tiếng Anh hoặc tiếng Nhật theo convention của team."

---

**Q19: "Bạn debug lỗi thế nào khi deploy trên Docker?"**

> "Khi có lỗi trong Docker: 1) `docker logs <container>` xem Apache/PHP errors. 2) Trong container: `docker exec -it <container> bash` rồi `cat storage/logs/laravel.log`. 3) Với SQL error, tôi check migration status `php artisan migrate:status`. 4) `APP_DEBUG=true` trong development để xem stack trace, `false` trong production. 5) Laravel Telescope (nếu installed) cho request/query debugging."

---

**Q20: "Nếu bạn phải thêm real-time notification (khi admin cập nhật order status, user nhận notification ngay), bạn implement thế nào?"**

> "Tôi thấy Firebase đã được install trong dự án nhưng chưa implement. Approach: 1) Khi admin update order status, fire Laravel Event. 2) Event Listener broadcast qua Laravel Broadcasting (hoặc trực tiếp Firebase FCM). 3) Frontend subcribe Firebase/WebSocket, nhận notification. Alternative nhẹ hơn: polling (client fetch API mỗi 30s), hoặc Laravel Echo + Pusher. Với stack hiện tại (Blade SSR), Server-Sent Events cũng là option đơn giản."

---

## 9. Cheat Sheet - Từ Khóa Quan Trọng

### Khi nói về dự án, hãy dùng những cụm từ này:

| Tiếng Việt | Kỹ thuật nói trong phỏng vấn |
|-----------|-------------------------------|
| Xác thực người dùng | "Session-based authentication với dual guard system" |
| Quản lý ngôn ngữ | "Session-based locale switching với Laravel's built-in i18n" |
| Bảo vệ route | "Middleware-based access control" |
| Cơ sở dữ liệu | "Eloquent ORM với database migration versioning" |
| Giao diện | "Server-side rendering với Blade template engine" |
| Build tool | "Vite bundler với Laravel plugin" |
| Triển khai | "Dockerized deployment với automated migration" |
| Giỏ hàng khách | "Session-based pending cart with post-login restoration" |

---

## 10. Câu Mở Đầu Khi Giới Thiệu Dự Án

> "CoffeeBlend là web application cho quán cà phê, bao gồm chức năng e-commerce (menu, giỏ hàng, thanh toán) và đặt bàn. Tôi build bằng Laravel 12 theo MVC pattern với Blade template engine cho server-side rendering. Điểm kỹ thuật đáng nói là dual authentication system tách biệt user và admin, session-based multi-language support (Anh/Nhật), custom middleware để bảo vệ checkout flow, và Dockerized deployment với automated database migration. Database dùng SQLite cho development với đầy đủ migration files để dễ dàng switch sang MySQL khi production."

---

*Tài liệu được tạo ngày 2026-03-01. Cập nhật khi dự án có thay đổi.*
