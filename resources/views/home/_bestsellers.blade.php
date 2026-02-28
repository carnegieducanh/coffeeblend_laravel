<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
        <span class="subheading">{{ __('messages.discover') }}</span>
        <h2 class="mb-4">{{ __('messages.best_sellers') }}</h2>
        <p>{{ __('messages.bestsellers_desc') }}</p>
      </div>
    </div>
    <div class="row">

      @foreach ($products as $product)
      <div class="col-md-3 d-flex">
        <div class="menu-entry w-100 d-flex flex-column">
          <a href="{{ route('product.single', $product->id) }}" class="img"
            style="background-image: url({{ str_starts_with($product->image, 'http') ? $product->image : asset('assets/images/'.$product->image) }});"></a>
          <div class="text text-center pt-4 d-flex flex-column flex-grow-1">
            <h3><a href="{{ route('product.single', $product->id) }}">{{ $product->name }}</a></h3>
            <p class="product-description">{{ $product->localized_description }}</p>
            <div class="mt-auto">
              <p class="price"><span>${{ $product->price }}</span></p>
              <p><a href="{{ route('product.single', $product->id) }}"
                  class="btn btn-primary btn-outline-primary">{{ __('messages.btn_view_details') }}</a></p>
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>