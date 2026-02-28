@extends('layouts.app')

@if(request()->query('from') === 'order')
@section('page-notice')
<div class="w-100 py-2 text-center"
  style="background:#513e00; border-bottom:1px solid #ab8104; position:relative; z-index:1040;">
  <i class="icon-coffee mr-2" style="color:#bbbaba;"></i>{{ __('messages.flash_login_to_order') }}
</div>
@endsection
@endif

@section('content')

<section class="home-slider owl-carousel">

  <div class="slider-item"
    style="background-image: url({{ asset('assets/images/bg_1.jpg') }}); margin-top: -40px; position: relative;">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.login') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span>
            <span>{{ __('messages.login') }}</span>
          </p>
        </div>

      </div>
    </div>
    @include('partials._scroll_hint')
  </div>
</section>


<section class="ftco-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <form method="POST" action="{{ route('login') }}" class="billing-form ftco-bg-dark p-3 p-md-5">
          @csrf
          <h3 class="mb-4 billing-heading">{{ __('messages.login') }}</h3>
          <div class="row align-items-end">
            <div class="col-md-12">
              <div class="form-group">
                <label for="Email">{{ __('messages.label_email_field') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                  value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="Password">{{ __('messages.label_password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                  name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

            </div>
            <div class="col-md-12">
              <div class="form-group mt-4">
                <div class="radio">
                  <button name="submit" type="submit"
                    class="btn btn-primary py-3 px-4">{{ __('messages.btn_login') }}</button>
                </div>
                <div class="mt-3">
                  <a href="{{ route('view.login') }}"
                    style="color: #888; font-size: 0.85rem; text-decoration: none;">
                    {{ __('messages.login_as_admin') }}
                  </a>
                </div>
              </div>
            </div>


        </form><!-- END -->
      </div> <!-- .col-md-8 -->
    </div>
  </div>
  </div>
</section> <!-- .section -->


@endsection