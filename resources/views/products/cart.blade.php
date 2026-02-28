@extends('layouts.app')

@section('content')

<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">{{ __('messages.cart_page') }}</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></span>
            <span>{{ __('messages.cart_page') }}</span>
          </p>
        </div>

      </div>
    </div>
  </div>
</section>

<div class="container">
  @if(Session::has('delete'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('delete') }}</p>
  @endif
</div>

<section class="ftco-section ftco-cart">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <div class="cart-list">
          <table class="table-dark" style="width: 1100px; table-layout: fixed;">
            <colgroup>
              <col style="width: 50px;">
              <col style="width: 120px;">
              <col style="width: auto;">
              <col style="width: 110px;">
              <col style="width: 180px;">
              <col style="width: 110px;">
            </colgroup>
            <thead style="background-color: #c49b63; height: 60px">
              <tr class="text-center">
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>{{ __('messages.col_product') }}</th>
                <th>{{ __('messages.col_price') }}</th>
                <th>{{ __('messages.col_quantity') }}</th>
                <th>{{ __('messages.col_total') }}</th>
              </tr>
            </thead>
            <tbody>
              @if($cartProducts->count() > 0)
              @foreach ($cartProducts as $cartProduct)
              <tr class="text-center" style="height: 140px">
                <td class="product-remove">
                  <button class="btn-delete-cart"
                    data-url="{{ route('cart.product.delete', $cartProduct->pro_id) }}"
                    data-toggle="modal" data-target="#confirmDeleteModal"
                    style="background: none; border: none; outline: none; box-shadow: none; cursor: pointer; color: #c0392b; font-size: 1.5rem; transition: color 0.2s, transform 0.15s;"
                    onmouseover="this.style.color='#e74c3c'; this.style.transform='scale(1.2)';"
                    onmouseout="this.style.color='#c0392b'; this.style.transform='scale(1)';">
                    <span class="ion-ios-trash"></span>
                  </button>
                </td>

                <td class="image-prod"><img width="100" height="80"
                    src="{{ str_starts_with($cartProduct->image, 'http') ? $cartProduct->image : asset('assets/images/'.$cartProduct->image) }}">
                </td>

                <td class="product-name">
                  <h3>{{ $cartProduct->name }}</h3>
                  <p>{{ $cartProduct->description }}</p>
                </td>

                <td class="price" data-unit-price="{{ $cartProduct->price }}">
                  ${{ number_format($cartProduct->price, 2) }}</td>

                <td>
                  <div class="input-group justify-content-center" style="width: 170px; margin: 0 auto;">
                    <div class="input-group-prepend">
                      <button class="btn btn-outline-secondary qty-btn qty-minus" type="button" style="width: 44px; font-size: 1.1rem;">−</button>
                    </div>
                    <input type="text" name="quantity" class="quantity form-control input-number text-center" value="1"
                      min="1" max="100" data-price="{{ $cartProduct->price }}"
                      style="max-width: 50px; text-align: center;">
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary qty-btn qty-plus" type="button" style="width: 44px; font-size: 1.1rem;">+</button>
                    </div>
                  </div>
                </td>

                <td class="total row-total">${{ number_format($cartProduct->price, 2) }}</td>
              </tr><!-- END TR-->
              @endforeach

              @else
              <tr>
                <td colspan="6" class="text-center" style="padding: 3rem 0; border: none;">
                  <i class="icon-coffee" style="font-size: 3rem; color: #c49b63; display: block; margin-bottom: 0.75rem;"></i>
                  <p style="color: #bbbaba; font-size: 1.05rem; margin: 0;">{{ __('messages.empty_cart') }}</p>
                </td>
              </tr>
              @endif


            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row justify-content-end">
      <div class="col col-lg-3 col-md-6 mt-5 cart-wrap ftco-animate">
        <div class="cart-total mb-3">
          <h3>{{ __('messages.cart_totals') }}</h3>
          <p class="d-flex">
            <span>{{ __('messages.subtotal') }}</span>
            <span id="cart-subtotal">${{ number_format($totalPrice, 2) }}</span>
          </p>
          <p class="d-flex">
            <span>{{ __('messages.delivery') }}</span>
            <span>$0.00</span>
          </p>
          <hr>
          <p class="d-flex total-price">
            <span>{{ __('messages.total') }}</span>
            <span id="cart-total">${{ number_format($totalPrice, 2) }}</span>
          </p>
        </div>
        @if($cartProducts->count() > 0)
        <form method="POST" action="{{ route('prepare.checkout') }}">
          @csrf
          <input name="price" type="hidden" value="{{ $totalPrice }}" id="checkout-price">
          <input type="submit" value="{{ __('messages.btn_proceed_checkout') }}" name="submit"
            class="btn btn-primary py-3 px-4">

        </form>
        @else

        <p class="text-center alert alert-success">{{ __('messages.cannot_checkout') }}</p>
        @endif
      </div>
    </div>
  </div>
</section>

@if($relatedProducts->count() > 0)
<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
        <span class="subheading">{{ __('messages.discover') }}</span>
        <h2 class="mb-4">{{ __('messages.you_might_also_like') }}</h2>
        <p>{{ __('messages.handpicked_for_you') }}</p>
      </div>
    </div>
    <div class="row">
      @foreach($relatedProducts as $relatedProduct)
      <div class="col-md-3">
        <div class="menu-entry">
          <a href="{{ route('product.single', $relatedProduct->id) }}" class="img"
            style="background-image: url({{ str_starts_with($relatedProduct->image, 'http') ? $relatedProduct->image : asset('assets/images/'.$relatedProduct->image) }});"></a>
          <div class="text text-center pt-4">
            <h3><a href="{{ route('product.single', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a></h3>
            <p>{{ Str::limit($relatedProduct->description, 60) }}</p>
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


{{-- Modal xác nhận xóa sản phẩm --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #1d1d1d; color: #fff; border: 1px solid #c49b63; border-radius: 8px;">
      <div class="modal-header" style="border-bottom: 1px solid #c49b63;">
        <h5 class="modal-title" id="confirmDeleteLabel" style="color: #c49b63;">
          <span class="ion-ios-trash mr-2"></span>{{ __('messages.confirm_delete_title') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center" style="padding: 2rem 1.5rem;">
        <p style="font-size: 1rem; margin: 0;">{{ __('messages.confirm_delete_cart') }}</p>
      </div>
      <div class="modal-footer" style="border-top: 1px solid #333; justify-content: center; gap: 1rem;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px;">
          {{ __('messages.btn_cancel') }}
        </button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger" style="min-width: 100px;">
          <span class="ion-ios-trash mr-1"></span>{{ __('messages.btn_delete') }}
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    function updateTotals() {
      let subtotal = 0;

      document.querySelectorAll('.quantity').forEach(function(input) {
        const qty = parseInt(input.value) || 1;
        const price = parseFloat(input.dataset.price) || 0;
        const row = input.closest('tr');
        const rowTotal = row.querySelector('.row-total');
        const lineTotal = qty * price;

        rowTotal.textContent = '$' + lineTotal.toFixed(2);
        subtotal += lineTotal;
      });

      document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
      document.getElementById('cart-total').textContent = '$' + subtotal.toFixed(2);

      const checkoutPrice = document.getElementById('checkout-price');
      if (checkoutPrice) checkoutPrice.value = subtotal.toFixed(2);
    }

    // Gán URL xóa vào nút xác nhận trong modal
    document.querySelectorAll('.btn-delete-cart').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('confirmDeleteBtn').href = this.dataset.url;
      });
    });

    document.querySelectorAll('.qty-minus').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const input = this.closest('.input-group').querySelector('.quantity');
        let val = parseInt(input.value) || 1;
        if (val > 1) {
          input.value = val - 1;
          updateTotals();
        }
      });
    });

    document.querySelectorAll('.qty-plus').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const input = this.closest('.input-group').querySelector('.quantity');
        let val = parseInt(input.value) || 1;
        if (val < 100) {
          input.value = val + 1;
          updateTotals();
        }
      });
    });

    document.querySelectorAll('.quantity').forEach(function(input) {
      input.addEventListener('change', function() {
        let val = parseInt(this.value) || 1;
        if (val < 1) val = 1;
        if (val > 100) val = 100;
        this.value = val;
        updateTotals();
      });
    });

  });
</script>

@endsection
