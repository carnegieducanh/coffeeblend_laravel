<style>
.gallery-scroll-wrapper {
  overflow: hidden;
  width: 100%;
}

.gallery-track {
  display: flex;
  gap: 10px;
  width: max-content;
  animation: galleryScroll 50s linear infinite;
}

.gallery-track:hover {
  animation-play-state: paused;
}

.gallery-item {
  flex: 0 0 auto;
  width: 300px;
}

.gallery-item .gallery.img {
  width: 300px;
  height: 300px;
  background-size: cover;
  background-position: center;
}

@keyframes galleryScroll {
  0% {
    transform: translateX(0);
  }

  100% {
    transform: translateX(-50%);
  }
}
</style>

<section class="ftco-gallery">
  <div class="gallery-scroll-wrapper">
    <div class="gallery-track">
      {{-- Bộ ảnh gốc --}}
      <div class="gallery-item ftco-animate">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-1.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item ftco-animate">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-2.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item ftco-animate">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-3.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item ftco-animate">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-4.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item ftco-animate">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-1.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>

      {{-- Nhân đôi để tạo vòng lặp liền mạch --}}
      <div class="gallery-item">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-2.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-3.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-4.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-1.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
      <div class="gallery-item">
        <a href="gallery.html" class="gallery img d-flex align-items-center"
          style="background-image: url({{ asset('assets/images/gallery-2.jpg') }});">
          <div class="icon mb-4 d-flex align-items-center justify-content-center">
            <span class="icon-search"></span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>