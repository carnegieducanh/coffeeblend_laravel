@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon"><i class="fa-solid fa-mug-hot"></i></div>
                <h5 class="card-title">Products</h5>
                <div class="stat-number">{{ $productsCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
                <h5 class="card-title">Orders</h5>
                <div class="stat-number">{{ $orderssCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <h5 class="card-title">Bookings</h5>
                <div class="stat-number">{{ $bookingsCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <h5 class="card-title">Admins</h5>
                <div class="stat-number">{{ $adminsCount }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
