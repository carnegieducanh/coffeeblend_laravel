<section class="home-slider owl-carousel">
  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_1.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

        <div class="col-md-8 col-sm-12 text-center ftco-animate">
          <span class="subheading">{{ __('messages.hero_slide1_sub') }}</span>
          <h1 class="mb-4">{{ __('messages.hero_slide1_title') }}</h1>
          <p class="mb-4 mb-md-5">{{ __('messages.hero_slide1_desc') }}</p>
          <p><a href="{{ Auth::check() ? route('users.orders') : route('login', ['from' => 'order']) }}"
              class="btn btn-primary p-3 px-xl-4 py-xl-3">{{ __('messages.btn_order_now') }}</a> <a href="{{ route('products.menu') }}"
              class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">{{ __('messages.btn_view_menu') }}</a></p>
        </div>

      </div>
    </div>
  </div>

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_2.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

        <div class="col-md-8 col-sm-12 text-center ftco-animate">
          <span class="subheading">{{ __('messages.hero_slide2_sub') }}</span>
          <h1 class="mb-4">{!! __('messages.hero_slide2_title') !!}</h1>
          <p class="mb-4 mb-md-5">{{ __('messages.hero_slide2_desc') }}</p>
          <p><a href="{{ Auth::check() ? route('users.orders') : route('login', ['from' => 'order']) }}"
              class="btn btn-primary p-3 px-xl-4 py-xl-3">{{ __('messages.btn_order_now') }}</a> <a href="{{ route('products.menu') }}"
              class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">{{ __('messages.btn_view_menu') }}</a></p>
        </div>

      </div>
    </div>
  </div>

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

        <div class="col-md-8 col-sm-12 text-center ftco-animate">
          <span class="subheading">{{ __('messages.hero_slide3_sub') }}</span>
          <h1 class="mb-4">{{ __('messages.hero_slide3_title') }}</h1>
          <p class="mb-4 mb-md-5">{{ __('messages.hero_slide3_desc') }}</p>
          <p><a href="{{ Auth::check() ? route('users.orders') : route('login', ['from' => 'order']) }}"
              class="btn btn-primary p-3 px-xl-4 py-xl-3">{{ __('messages.btn_order_now') }}</a> <a href="{{ route('products.menu') }}"
              class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">{{ __('messages.btn_view_menu') }}</a></p>
        </div>

      </div>
    </div>
  </div>
</section>
