@extends('layouts.app')

@section('content')
<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.write_review') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span>
            <span>{{ __('messages.write_review') }}</span>
          </p>
        </div>
        @include('partials._scroll_hint')
      </div>
    </div>
  </div>
</section>

<div class="container">
  @if(Session::has('reviews'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('reviews') }}</p>
  @endif
</div>
<section class="ftco-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <form method="POST" action="{{ route('proccess.write.review') }}" class="billing-form ftco-bg-dark p-3 p-md-5">
          <h3 class="mb-4 billing-heading">{{ __('messages.write_review') }}</h3>
          @csrf

          <div class="w-100"></div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="streetaddress">{{ __('messages.write_review') }}</label>
              <textarea name="review" cols="10" rows="10" class="form-control"
                placeholder="{{ __('messages.placeholder_write_review') }}"></textarea>
            </div>
          </div>


          <div class="w-100"></div>
          <div class="col-md-12">
            <div class="form-group mt-4">
              <div class="radio">
                <button type="submit" name="submit"
                  class="btn btn-primary py-3 px-4">{{ __('messages.btn_write_review') }}</button>

              </div>
            </div>
          </div>
      </div>
      </form><!-- END -->
</section>
@endsection