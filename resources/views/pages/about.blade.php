@extends('layouts.app')

@section('content')
<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.about_us') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span> <span>{{ __('messages.about') }}</span></p>
        </div>

      </div>
    </div>
  </div>
</section>

<section class="ftco-about d-md-flex">
  <div class="one-half img" style="background-image: url({{ asset('assets/images/about.jpg') }});"></div>
  <div class="one-half ftco-animate">
    <div class="overlap">
      <div class="heading-section ftco-animate ">
        <span class="subheading">{{ __('messages.discover') }}</span>
        <h2 class="mb-4">{{ __('messages.our_story') }}</h2>
      </div>
      <div>
        <p>{{ __('messages.about_p1') }}</p>
        <p>{{ __('messages.about_p2') }}</p>
      </div>
    </div>
  </div>
</section>

@include('partials._testimony')

<section class="ftco-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 pr-md-5">
        <div class="heading-section text-md-right ftco-animate">
          <span class="subheading">{{ __('messages.discover') }}</span>
          <h2 class="mb-4">{{ __('messages.our_menu') }}</h2>
          <p class="mb-4">{{ __('messages.about_menu_desc') }}</p>
          <p><a href="{{ route('products.menu') }}" class="btn btn-primary btn-outline-primary px-4 py-3">{{ __('messages.btn_view_full_menu') }}</a></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <div class="menu-entry">
              <a href="#" class="img" style="background-image: url(images/menu-1.jpg);"></a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="menu-entry mt-lg-4">
              <a href="#" class="img" style="background-image: url(images/menu-2.jpg);"></a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="menu-entry">
              <a href="#" class="img" style="background-image: url(images/menu-3.jpg);"></a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="menu-entry mt-lg-4">
              <a href="#" class="img" style="background-image: url(images/menu-4.jpg);"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('partials._counter')
@endsection