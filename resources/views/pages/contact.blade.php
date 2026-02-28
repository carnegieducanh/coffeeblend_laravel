@extends('layouts.app')

@section('content')


<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }}); position: relative;">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.contact_us') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span> <span>{{ __('messages.contact') }}</span>
          </p>
        </div>

      </div>
    </div>
    @include('partials._scroll_hint')
  </div>
</section>

<section class="ftco-section contact-section">
  <div class="container mt-5">
    <div class="row block-9">
      <div class="col-md-4 contact-info ftco-animate">
        <div class="row">
          <div class="col-md-12 mb-4">
            <h2 class="h4 text-secondary">{{ __('messages.contact_information') }}</h2>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>{{ __('messages.label_address') }}</span> 2-14-5 Dogenzaka, Shibuya-ku, Tokyo 150-0043, Japan</p>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>{{ __('messages.label_phone') }}</span> <a class="text-secondary" href="tel:+81356781234">+81 (0)3-5678-1234</a></p>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>{{ __('messages.label_email') }}</span> <a class="text-secondary" href="mailto:hello@coffeeblend.jp">hello@coffeeblend.jp</a>
            </p>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>{{ __('messages.label_hours') }}</span> {{ __('messages.info_hours') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-1"></div>
      <div class="col-md-6 ftco-animate">
        <form action="#" class="contact-form">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                @auth
                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                @else
                <input type="text" class="form-control" placeholder="{{ __('messages.placeholder_your_name') }}">
                @endauth
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                @auth
                <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                @else
                <input type="text" class="form-control" placeholder="{{ __('messages.placeholder_your_email') }}">
                @endauth
              </div>
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="{{ __('messages.placeholder_subject') }}">
          </div>
          <div class="form-group">
            <textarea name="" id="" cols="30" rows="7" class="form-control" placeholder="{{ __('messages.placeholder_message_contact') }}"></textarea>
          </div>
          <div class="form-group">
            <input type="submit" value="{{ __('messages.btn_send_message') }}" class="btn btn-primary py-3 px-5">
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

@endsection