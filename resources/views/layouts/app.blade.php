<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">


  <!-- mmy files -->

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/css/open-iconic-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/ionicons.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/jquery.timepicker.css') }}">


  <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/icomoon.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <!-- Scripts -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
  <div id="app">
    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Coffee<small>Blend</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
          aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item {{ request()->routeIs('home', 'index') ? 'active' : '' }}"><a href="{{ route('home') }}"
                class="nav-link">Home</a></li>
            <li class="nav-item {{ request()->routeIs('products.menu') ? 'active' : '' }}"><a
                href="{{ route('products.menu') }}" class="nav-link">Menu</a></li>
            <li class="nav-item {{ request()->routeIs('services') ? 'active' : '' }}"><a href="{{ route('services') }}"
                class="nav-link">Services</a></li>
            <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}"
                class="nav-link">About</a></li>
            <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}"
                class="nav-link">Contact</a></li>

            @auth
            @php $cartCount = \App\Models\Product\Cart::where('user_id', Auth::id())->count(); @endphp
            <li class="nav-item cart">
              <a href="{{ route('cart') }}" class="nav-link">
                <span style="position: relative; display: inline-block;">
                  <span class="icon icon-shopping_cart"></span>
                  @if($cartCount > 0)
                  <span
                    style="position: absolute; top: -10px; right: -10px; background: #e74c3c; color: #fff; border-radius: 50%; font-size: 10px; min-width: 17px; height: 17px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; line-height: 1;">{{ $cartCount }}</span>
                  @endif
                </span>
              </a>
            </li>
            @endauth
            @guest
            @if (Route::has('login'))
            <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">login</a></li>
            @endif

            @if (Route::has('register'))

            <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">register</a></li>
            @endif
            @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
              </a>

              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="{{ route('users.orders') }}">
                  My Orders
                </a>
                <a class="dropdown-item" href="{{ route('users.bookings') }}">
                  My Bookings
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <!-- MAIN -->
    <main class="py-4">
      @yield('content')
    </main>
  </div>

  <!-- FOOTER -->
  <footer class="ftco-footer ftco-section img">
    <div class="overlay"></div>
    <div class="container">
      <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">About Us</h2>
            <p>CoffeeBlend is a specialty coffee café in the heart of Shibuya, Tokyo. We craft every cup with passion,
              precision, and the finest beans from around the world.</p>
            <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
              <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Quick Links</h2>
            <ul class="list-unstyled">
              <li><a href="{{ route('home') }}" class="py-2 d-block">Home</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">Our Menu</a></li>
              <li><a href="{{ route('services') }}" class="py-2 d-block">Services</a></li>
              <li><a href="{{ route('about') }}" class="py-2 d-block">About Us</a></li>
              <li><a href="{{ route('contact') }}" class="py-2 d-block">Contact</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Services</h2>
            <ul class="list-unstyled">
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">Dine In</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">Online Order</a></li>
              <li><a href="{{ route('home') }}#" class="py-2 d-block">Table Booking</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">Coffee Delivery</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">Seasonal Specials</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Have a Question?</h2>
            <ul class="list-unstyled footer-contact">
              <li class="d-flex mb-3">
                <span class="icon icon-map-marker mr-3" style="padding-top:3px; min-width:18px;"></span>
                <span style="color: rgba(255,255,255,0.7);">2-14-5 Dogenzaka, Shibuya-ku, Tokyo 150-0043, Japan</span>
              </li>
              <li class="d-flex align-items-center mb-3">
                <a href="tel:+81356781234" class="d-flex align-items-center">
                  <span class="icon icon-phone mr-3" style="min-width:18px;"></span>
                  <span>+81 (0)3-5678-1234</span>
                </a>
              </li>
              <li class="d-flex align-items-center mb-3">
                <a href="mailto:hello@coffeeblend.jp" class="d-flex align-items-center">
                  <span class="icon icon-envelope mr-3" style="min-width:18px;"></span>
                  <span>hello@coffeeblend.jp</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">

          <p>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>
            document.write(new Date().getFullYear());
            </script> All rights reserved</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
          </p>
        </div>
      </div>
    </div>
  </footer>


  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
        stroke="#F96D00" />
    </svg></div>

  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery-migrate-3.0.1.min.js') }}"></script>
  <script src="{{ asset('assets/js/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.easing.1.3.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.stellar.min.js') }}"></script>
  <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('assets/js/aos.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.animateNumber.min.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>
  <script src="{{ asset('assets/js/scrollax.min.js') }}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false">
  </script>
  <script src="{{ asset('assets/js/google-map.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>