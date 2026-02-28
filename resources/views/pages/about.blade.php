@extends('layouts.app')

@section('content')
<section class="home-slider owl-carousel">

  <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">

        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">About Us</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>About</span></p>
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
        <span class="subheading">Discover</span>
        <h2 class="mb-4">Our Story</h2>
      </div>
      <div>
        <p>CoffeeBlend was born in the vibrant streets of Shibuya, Tokyo — founded by a group of passionate baristas who
          believed that a great cup of coffee is more than just a drink. It’s a ritual, a moment of calm, a connection
          between people.</p>
        <p>Inspired by Japan’s deep culture of craftsmanship and attention to detail, we source the finest single-origin
          beans from Ethiopia, Colombia, and Guatemala, roasted in small batches to bring out every layer of flavor.
          Whether you’re a seasoned coffee enthusiast or simply looking for a welcoming place to start your morning,
          CoffeeBlend is your home in Tokyo.</p>
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
          <span class="subheading">Discover</span>
          <h2 class="mb-4">Our Menu</h2>
          <p class="mb-4">From rich espresso classics to seasonal Japanese-inspired specials, our menu is crafted to
            delight every palate. Explore our full selection of hot and cold drinks, light bites, and handcrafted
            pastries — all made fresh daily at our Shibuya kitchen.</p>
          <p><a href="{{ route('products.menu') }}" class="btn btn-primary btn-outline-primary px-4 py-3">View Full
              Menu</a></p>
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