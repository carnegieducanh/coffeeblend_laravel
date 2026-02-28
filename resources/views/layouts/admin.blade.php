<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Panel – CoffeeBlend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/styles/style.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar header-top fixed-top navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ Auth::guard('admin')->check() ? route('admins.dashboard') : '#' }}">
                    <span class="brand-coffee">Coffee</span><span class="brand-blend">Blend</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarText">

                    @auth('admin')
                    <ul class="navbar-nav side-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admins.dashboard') ? 'active' : '' }}"
                                href="{{ route('admins.dashboard') }}">
                                <i class="fa-solid fa-house"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('all.admins', 'create.admins') ? 'active' : '' }}"
                                href="{{ route('all.admins') }}">
                                <i class="fa-solid fa-users"></i> Admins
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('all.orders', 'edit.order') ? 'active' : '' }}"
                                href="{{ route('all.orders') }}">
                                <i class="fa-solid fa-box"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('all.products', 'create.products') ? 'active' : '' }}"
                                href="{{ route('all.products') }}">
                                <i class="fa-solid fa-mug-hot"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('all.bookings', 'edit.booking') ? 'active' : '' }}"
                                href="{{ route('all.bookings') }}">
                                <i class="fa-solid fa-calendar-days"></i> Bookings
                            </a>
                        </li>
                    </ul>
                    @endauth

                    <ul class="navbar-nav ml-md-auto d-md-flex">
                        @auth('admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admins.dashboard') }}">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" title="Back to homepage">
                                <i class="fa-solid fa-arrow-left"></i> Back to Site
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-user-circle"></i>
                                {{ Auth::guard('admin')->user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" title="Back to homepage">
                                <i class="fa-solid fa-house"></i> Back to Site
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('view.login') }}">
                                <i class="fa-solid fa-right-to-bracket"></i> Login
                            </a>
                        </li> -->
                        @endauth
                    </ul>

                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>