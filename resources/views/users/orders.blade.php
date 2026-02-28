@extends('layouts.app')

@section('content')

<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.my_orders') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span>
            <span>{{ __('messages.my_orders') }}</span>
          </p>
        </div>
        @include('partials._scroll_hint')
      </div>
    </div>
  </div>
</section>


<section class="ftco-section ftco-cart">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <div class="cart-list">
          <table class="table-dark" style="width: 1100px">
            <thead style="background-color: #c49b63; height: 60px">
              <tr class="text-center">
                <th>{{ __('messages.col_name') }}</th>
                <th>{{ __('messages.col_city') }}</th>
                <th>{{ __('messages.col_email_header') }}</th>
                <th>{{ __('messages.col_price') }}</th>
                <th>{{ __('messages.col_status') }}</th>
                <th>{{ __('messages.btn_write_review') }}</th>

              </tr>
            </thead>
            <tbody>
              @if($orders->count() > 0)
              @foreach ($orders as $order)
              <tr class="text-center" style="height: 140px">
                <td class="product-remove">{{ $order->name }}</td>

                <td class="price">{{ $order->city }}</td>

                <td>
                  {{ $order->email }}
                </td>

                <td class="total">{{ $order->price }}</td>
                <td class="total">{{ $order->status }}</td>
                <td class="total">
                  @if($order->status == "Delivered")
                  <a class="btn btn-primary"
                    href="{{ route('write.reviews') }}">{{ __('messages.btn_write_review') }}</a>
                  @else
                  <span>{{ __('messages.not_available_yet') }}</span>
                  @endif
                </td>
              </tr><!-- END TR-->
              @endforeach
              @else
              <tr>
                <td colspan="6" class="text-center" style="padding: 3rem 0; border: none;">
                  <i class="icon-coffee"
                    style="font-size: 3rem; color: #c49b63; display: block; margin-bottom: 0.75rem;"></i>
                  <p style="color: #bbbaba; font-size: 1.05rem; margin: 0;">{{ __('messages.no_orders') }}</p>
                </td>
              </tr>
              @endif


            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</section>


@endsection