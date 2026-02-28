@extends('layouts.app')

@section('styles')
<style>
  .billing-form .form-control[readonly] {
    cursor: not-allowed;
    opacity: 0.75;
  }

  .billing-form .select-wrap select.form-control {
    background-color: #c49b63;
    color: #000;
  }

  .billing-form .select-wrap select.form-control option {
    background-color: #c49b63;
    color: #000;
  }
</style>
@endsection

@section('content')
<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }}); position: relative;">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.checkout_page') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span>
            <span>{{ __('messages.checkout_page') }}</span>
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
        <form method="POST" action="{{ route('proccess.checkout') }}" class="billing-form ftco-bg-dark p-3 p-md-5">
          <h3 class="mb-4 billing-heading">{{ __('messages.billing_details') }}</h3>
          @csrf
          <div class="row align-items-end">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name">{{ __('messages.label_name') }}</label>
                <input type="text" name="name" class="form-control" placeholder=""
                  value="{{ Auth::check() ? Auth::user()->name : '' }}" {{ Auth::check() ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="w-100"></div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="country">{{ __('messages.label_state_country') }}</label>
                <div class="select-wrap">
                  <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                  <select name="state" id="" class="form-control">
                    <option value="Tokyo">Tokyo (東京都)</option>
                    <option value="Kanagawa">Kanagawa (神奈川県)</option>
                    <option value="Saitama">Saitama (埼玉県)</option>
                    <option value="Chiba">Chiba (千葉県)</option>
                    <option value="Ibaraki">Ibaraki (茨城県)</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="w-100"></div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="streetaddress">{{ __('messages.label_street_address') }}</label>
                <textarea name="address" cols="3" rows="3" class="form-control"
                  placeholder="{{ __('messages.placeholder_house_number') }}"></textarea>
              </div>
            </div>
            {{-- <div class="col-md-12">
                      <div class="form-group">
                    <input type="text" class="form-control" placeholder="Appartment, suite, unit etc: (optional)">
                  </div> 
                  </div> --}}
            <div class="w-100"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="towncity">{{ __('messages.label_town_city') }}</label>
                <input name="city" type="text" class="form-control" placeholder="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="postcodezip">{{ __('messages.label_postcode_zip') }}</label>
                <input name="zip_code" type="text" class="form-control" placeholder="">
              </div>
            </div>
            <div class="w-100"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="phone">{{ __('messages.label_phone_field') }}</label>
                <input name="phone" type="text" class="form-control" placeholder="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="emailaddress">{{ __('messages.label_email_address') }}</label>
                <input name="email" type="text" class="form-control" placeholder=""
                  value="{{ Auth::check() ? Auth::user()->email : '' }}" {{ Auth::check() ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <input name="price" type="hidden" value="{{ Session::get('price') }}" class="form-control"
                  placeholder="">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <input name="user_id" type="hidden" value="{{ Auth::user()->id }}" class="form-control" placeholder="">
              </div>
            </div>


            <div class="w-100"></div>
            <div class="col-md-12">
              <div class="form-group mt-4">
                <div class="radio">
                  <button type="submit" name="submit"
                    class="btn btn-primary py-3 px-4">{{ __('messages.btn_place_order') }}</button>

                </div>
              </div>
            </div>
          </div>
        </form><!-- END -->
</section>
@endsection