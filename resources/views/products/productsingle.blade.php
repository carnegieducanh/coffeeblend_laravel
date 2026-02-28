@extends('layouts.app')

@section('content')
<style>
.btn.btn-primary.btn-outline-primary.py-3.px-5:hover {
  color: #fff !important;
}

/* Related products: card có chiều cao bằng nhau, giá & nút thẳng hàng */
.related-section .col-md-3 {
  display: flex;
  margin-bottom: 2rem;
}
.related-section .menu-entry {
  display: flex;
  flex-direction: column;
  width: 100%;
}
.related-section .menu-entry .text {
  display: flex;
  flex-direction: column;
  flex: 1;
}
.related-section .related-desc {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  line-clamp: 3;
  overflow: hidden;
  flex: 1;
  margin-bottom: 0.75rem;
}
.related-section .menu-entry .price {
  margin-bottom: 0.5rem;
}
</style>

<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.product_detail') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span> <span>{{ __('messages.product_detail') }}</span></p>
        </div>

      </div>
    </div>
  </div>
</section>


<section class="ftco-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mb-5 ftco-animate">
        <a href="{{ str_starts_with($product->image, 'http') ? $product->image : asset('assets/images/'.$product->image) }}"
          class="image-popup">
          <img
            src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('assets/images/'.$product->image) }}"
            class="img-fluid" alt="Colorlib Template">
        </a>
      </div>
      <div class="col-lg-6 product-details pl-md-5 ftco-animate">
        <h3 class="text-white">{{ $product->name }}</h3>
        <p class="price"><span>${{ $product->price }}</span></p>
        <p>
          {{ $product->localized_description }}
        </p>
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="form-group d-flex">

            </div>


          </div>
        </div>
        <form method="POST" action="{{ route('add.cart', $product->id) }}">
          @csrf
          <input type="hidden" name="pro_id" value="{{ $product->id }}">
          <input type="hidden" name="name" value="{{ $product->name }}">
          <input type="hidden" name="price" value="{{ $product->price }}">
          <input type="hidden" name="image" value="{{ $product->image }}">
          @if(Auth::check() && $checkingInCart > 0)
          <a style="background-color: #555;" class="text-white btn py-3 px-5" disabled>{{ __('messages.btn_already_in_cart') }}</a>
          @else
          <button type="submit" name="submit" class="btn btn-primary btn-outline-primary py-3 px-5">{{ __('messages.btn_add_to_cart_form') }}</button>
          @endif
        </form>
      </div>

    </div>

  </div>

</section>

@if($relatedProducts->count() > 0)
<section class="ftco-section related-section">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
        <span class="subheading">{{ __('messages.discover') }}</span>
        <h2 class="mb-4">{{ __('messages.related_products') }}</h2>
        <p>{{ __('messages.related_products_desc') }}</p>
      </div>
    </div>
    <div class="row">
      @foreach ($relatedProducts as $relatedProduct)
      <div class="col-md-3">
        <div class="menu-entry">
          <a href="{{ route('product.single', $relatedProduct->id) }}" class="img"
            style="background-image: url({{ str_starts_with($relatedProduct->image, 'http') ? $relatedProduct->image : asset('assets/images/'.$relatedProduct->image) }});"></a>
          <div class="text text-center pt-4">
            <h3><a href="{{ route('product.single', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a></h3>
            <p class="related-desc">{{ $relatedProduct->localized_description }}</p>
            <p class="price"><span>${{ number_format($relatedProduct->price, 2) }}</span></p>
            <p><a href="{{ route('product.single', $relatedProduct->id) }}"
                class="btn btn-primary btn-outline-primary">{{ __('messages.btn_view_details') }}</a></p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif


@endsection