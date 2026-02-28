<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

  <!-- mmy files -->

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">

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

  <style>
    body[data-lang="ja"] {
      font-family: 'Noto Sans JP', 'Poppins', sans-serif;
    }
    .lang-switcher {
      display: flex;
      align-items: center;
      gap: 2px;
    }
    .lang-btn {
      padding: 4px 8px !important;
      font-size: 13px;
      white-space: nowrap;
    }
    .lang-active {
      color: #c49b63 !important;
      font-weight: 700;
    }
    .lang-sep {
      color: rgba(255,255,255,0.3);
      line-height: 1;
    }
    #ftco-loader.fullscreen {
      background-image: url('{{ asset('assets/images/bg_1.jpg') }}');
      background-size: cover;
      background-position: center;
      background-color: transparent;
    }
    #ftco-loader.fullscreen::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.55);
    }
    #ftco-loader .circular {
      z-index: 1;
    }
    #ftco-loader .loader-text {
      position: absolute;
      left: 50%;
      top: calc(50% + 44px);
      transform: translateX(-50%);
      margin: 0;
      color: #c49b63;
      font-size: 16px;
      font-weight: 600;
      white-space: nowrap;
      letter-spacing: 1px;
      z-index: 1;
      text-shadow: 0 1px 4px rgba(0,0,0,0.6);
    }
    /* Flash Alerts */
    #flash-alerts-container {
      position: fixed;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1055;
      width: max-content;
      max-width: 92vw;
      padding-top: 8px;
      pointer-events: none;
    }
    #flash-alerts-container .flash-alert {
      pointer-events: auto;
      min-width: 260px;
      max-width: 560px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.22);
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 6px;
      padding-right: 2.5rem;
    }
  </style>

  <!-- Scripts -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  @yield('styles')
</head>

<body data-lang="{{ app()->getLocale() }}">
  <div id="app">
    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Coffee<small>Blend</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
          aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> {{ __('messages.nav_menu') }}
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item {{ request()->routeIs('home', 'index') ? 'active' : '' }}"><a href="{{ route('home') }}"
                class="nav-link">{{ __('messages.nav_home') }}</a></li>
            <li class="nav-item {{ request()->routeIs('products.menu') ? 'active' : '' }}"><a
                href="{{ route('products.menu') }}" class="nav-link">{{ __('messages.nav_menu') }}</a></li>
            <li class="nav-item {{ request()->routeIs('services') ? 'active' : '' }}"><a href="{{ route('services') }}"
                class="nav-link">{{ __('messages.nav_services') }}</a></li>
            <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}"
                class="nav-link">{{ __('messages.nav_about') }}</a></li>
            <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}"
                class="nav-link">{{ __('messages.nav_contact') }}</a></li>

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
            <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">{{ __('messages.nav_login') }}</a></li>
            @endif

            @if (Route::has('register'))

            <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">{{ __('messages.nav_register') }}</a></li>
            @endif
            @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
              </a>

              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="{{ route('users.orders') }}">
                  {{ __('messages.nav_my_orders') }}
                </a>
                <a class="dropdown-item" href="{{ route('users.bookings') }}">
                  {{ __('messages.nav_my_bookings') }}
                </a>
                <a class="dropdown-item" href="{{ route('users.account') }}">
                  {{ __('messages.nav_my_account') }}
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                  {{ __('messages.nav_logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </li>
            @endguest

            <!-- Language Switcher -->
            <li class="nav-item lang-switcher">
              <a href="{{ route('lang.switch', 'en') }}" class="nav-link lang-btn {{ app()->getLocale() == 'en' ? 'lang-active' : '' }}">🇺🇸 EN</a>
              <span class="lang-sep">|</span>
              <a href="{{ route('lang.switch', 'ja') }}" class="nav-link lang-btn {{ app()->getLocale() == 'ja' ? 'lang-active' : '' }}">🇯🇵 JP</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    @yield('page-notice')

    <!-- Flash Alerts -->
    <div id="flash-alerts-container">
      @include('layouts._flash_alerts')
    </div>

    <!-- MAIN -->
    <main>
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
            <h2 class="ftco-heading-2">{{ __('messages.footer_about_us') }}</h2>
            <p>{{ __('messages.footer_about_desc') }}</p>
            <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
              <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">{{ __('messages.footer_quick_links') }}</h2>
            <ul class="list-unstyled">
              <li><a href="{{ route('home') }}" class="py-2 d-block">{{ __('messages.nav_home') }}</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">{{ __('messages.footer_our_menu') }}</a></li>
              <li><a href="{{ route('services') }}" class="py-2 d-block">{{ __('messages.nav_services') }}</a></li>
              <li><a href="{{ route('about') }}" class="py-2 d-block">{{ __('messages.footer_about_link') }}</a></li>
              <li><a href="{{ route('contact') }}" class="py-2 d-block">{{ __('messages.nav_contact') }}</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">{{ __('messages.footer_services') }}</h2>
            <ul class="list-unstyled">
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">{{ __('messages.footer_dine_in') }}</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">{{ __('messages.footer_online_order') }}</a></li>
              <li><a href="{{ route('home') }}#" class="py-2 d-block">{{ __('messages.footer_table_booking') }}</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">{{ __('messages.footer_coffee_delivery') }}</a></li>
              <li><a href="{{ route('products.menu') }}" class="py-2 d-block">{{ __('messages.footer_seasonal_specials') }}</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">{{ __('messages.footer_have_question') }}</h2>
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
            </script> {{ __('messages.footer_copyright') }}</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
          </p>
        </div>
      </div>
    </div>
  </footer>


  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
        stroke="#F96D00" />
    </svg>
    <p class="loader-text">{{ __('messages.loading_please_wait') }}</p>
  </div>

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

  <script>
    $(document).ready(function () {
      var $navbar    = $('#ftco-navbar');
      var $container = $('#flash-alerts-container');

      if ($navbar.length && $container.length && $container.find('.flash-alert').length) {
        var updatePos = function () {
          $container.css('top', $navbar.outerHeight() + 'px');
        };
        updatePos();
        $(window).on('resize scroll', updatePos);

        // Tự động đóng alert sau 5 giây
        setTimeout(function () {
          $container.find('.flash-alert').fadeOut(400, function () {
            $(this).remove();
          });
        }, 5000);
      }

      // Đóng thủ công bằng nút ×
      $container.on('click', '.close', function () {
        $(this).closest('.flash-alert').fadeOut(300, function () {
          $(this).remove();
        });
      });
    });
  </script>

</body>

</html>
