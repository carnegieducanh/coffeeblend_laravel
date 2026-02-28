@extends('layouts.app')

@section('content')

<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.my_bookings') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span> <span>{{ __('messages.my_bookings') }}</span></p>
        </div>

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
                <th>{{ __('messages.col_date') }}</th>
                <th>{{ __('messages.col_time') }}</th>
                <th>{{ __('messages.col_phone_header') }}</th>
                <th>{{ __('messages.col_status') }}</th>
                <th>{{ __('messages.btn_write_review') }}</th>

              </tr>
            </thead>
            <tbody>
              @if($bookings->count() > 0)
              @foreach ($bookings as $booking)
              <tr class="text-center" style="height: 140px">
                <td class="product-remove">{{ $booking->name }}</td>

                <td class="price">{{ $booking->date }}</td>

                <td>
                  {{ $booking->time }}
                </td>

                <td class="total">{{ $booking->phone }}</td>
                <td class="total">{{ $booking->status }}</td>
                <td class="total">
                  @if($booking->status == "Booked")
                  <a class="btn btn-primary" href="{{ route('write.reviews') }}">{{ __('messages.btn_write_review') }}</a>
                  @else
                  <p>{{ __('messages.not_available_yet') }}</p>
                  @endif
                </td>
              </tr><!-- END TR-->
              @endforeach
              @else
              <tr>
                <td colspan="6" class="text-center" style="padding: 3rem 0; border: none;">
                  <i class="icon-coffee" style="font-size: 3rem; color: #c49b63; display: block; margin-bottom: 0.75rem;"></i>
                  <p style="color: #bbbaba; font-size: 1.05rem; margin: 0;">{{ __('messages.no_bookings') }}</p>
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