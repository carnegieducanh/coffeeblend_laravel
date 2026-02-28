<section class="home-slider owl-carousel">
  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_1.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

        <div class="col-md-8 col-sm-12 text-center ftco-animate">
          <span class="subheading">Welcome to CoffeeBlend Tokyo</span>
          <h1 class="mb-4">Where Every Sip Tells a Story</h1>
          <p class="mb-4 mb-md-5">Nestled in the heart of Tokyo, CoffeeBlend brings you handcrafted specialty coffee that blends Japanese precision with global coffee culture.</p>
          <p><a href="{{ Auth::check() ? route('users.orders') : route('login') }}"
              class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="{{ route('products.menu') }}"
              class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
        </div>

      </div>
    </div>
  </div>

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_2.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

        <div class="col-md-8 col-sm-12 text-center ftco-animate">
          <span class="subheading">A Warm Retreat in Tokyo</span>
          <h1 class="mb-4">Amazing Taste &amp; Beautiful Place</h1>
          <p class="mb-4 mb-md-5">Escape the city buzz and find your perfect cup. Our cozy café in Shibuya is designed to be your favorite corner in Tokyo — from morning espresso to evening latte.</p>
          <p><a href="{{ Auth::check() ? route('users.orders') : route('login') }}"
              class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="{{ route('products.menu') }}"
              class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
        </div>

      </div>
    </div>
  </div>

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

        <div class="col-md-8 col-sm-12 text-center ftco-animate">
          <span class="subheading">Freshly Brewed Daily</span>
          <h1 class="mb-4">Creamy, Hot and Ready to Serve</h1>
          <p class="mb-4 mb-md-5">From single-origin pour-overs to rich seasonal blends — each cup is crafted by our expert baristas using carefully sourced beans from around the world.</p>
          <p><a href="{{ Auth::check() ? route('users.orders') : route('login') }}"
              class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="{{ route('products.menu') }}"
              class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
        </div>

      </div>
    </div>
  </div>
</section>
