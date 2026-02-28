@extends('layouts.app')

@section('styles')
<style>
  .billing-form .form-control:-webkit-autofill,
  .billing-form .form-control:-webkit-autofill:hover,
  .billing-form .form-control:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #030202 inset !important;
    -webkit-text-fill-color: rgba(255, 255, 255, 0.4) !important;
    transition: background-color 5000s ease-in-out 0s;
  }
</style>
@endsection

@section('content')

<section class="home-slider owl-carousel">
  <div class="slider-item"
    style="background-image: url({{ asset('assets/images/bg_1.jpg') }}); margin-top: -40px; position: relative;">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">
        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.my_account') }}</h1>
          <p class="breadcrumbs">
            <span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span>
            <span>{{ __('messages.my_account') }}</span>
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

        @if(session('success'))
        <div class="alert alert-success mb-4" role="alert">
          {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('users.account.update') }}" class="billing-form ftco-bg-dark p-3 p-md-5">
          @csrf
          <h3 class="mb-4 billing-heading">{{ __('messages.my_account') }}</h3>

          <div class="row align-items-end">

            {{-- Name --}}
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">{{ __('messages.label_username') }}</label>
                <input id="name" type="text"
                  class="form-control @error('name') is-invalid @enderror"
                  name="name"
                  value="{{ old('name', Auth::user()->name) }}"
                  required autocomplete="name">
                @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div>

            {{-- Email --}}
            <div class="col-md-12">
              <div class="form-group">
                <label for="email">{{ __('messages.label_email_field') }}</label>
                <input id="email" type="email"
                  class="form-control @error('email') is-invalid @enderror"
                  name="email"
                  value="{{ old('email', Auth::user()->email) }}"
                  required autocomplete="email">
                @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div>

            {{-- Current Password --}}
            <div class="col-md-12">
              <div class="form-group">
                <label for="current_password">{{ __('messages.label_current_password') }}</label>
                <input id="current_password" type="password"
                  class="form-control @error('current_password') is-invalid @enderror"
                  name="current_password"
                  autocomplete="current-password"
                  placeholder="{{ __('messages.label_current_password') }}">
                @error('current_password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div>

            {{-- New Password --}}
            <div class="col-md-12">
              <div class="form-group">
                <label for="new_password">{{ __('messages.label_new_password') }}</label>
                <input id="new_password" type="password"
                  class="form-control @error('new_password') is-invalid @enderror"
                  name="new_password"
                  autocomplete="new-password"
                  placeholder="{{ __('messages.placeholder_new_password') }}">
                @error('new_password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div>

            {{-- Confirm New Password --}}
            <div class="col-md-12">
              <div class="form-group">
                <label for="new_password_confirmation">{{ __('messages.label_confirm_password') }}</label>
                <input id="new_password_confirmation" type="password"
                  class="form-control"
                  name="new_password_confirmation"
                  autocomplete="new-password"
                  placeholder="{{ __('messages.label_confirm_password') }}">
              </div>
            </div>

            {{-- Submit --}}
            <div class="col-md-12">
              <div class="form-group mt-4">
                <button name="submit" type="submit" class="btn btn-primary py-3 px-4">
                  {{ __('messages.btn_save_changes') }}
                </button>
              </div>
            </div>

          </div>
        </form><!-- END -->

      </div>
    </div>
  </div>
</section>

@endsection
