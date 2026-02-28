<style>
.review-scroll-wrapper {
  overflow: hidden;
  width: 100%;
}

.review-track {
  display: flex;
  gap: 20px;
  width: max-content;
  animation: reviewScroll 50s linear infinite;
}

.review-track:hover {
  animation-play-state: paused;
}

.review-item {
  flex: 0 0 auto;
  width: 340px;
  min-height: 220px;
}

.review-item .testimony {
  display: flex;
  flex-direction: column;
  min-height: 220px;
}

@keyframes reviewScroll {
  0% {
    transform: translateX(0);
  }

  100% {
    transform: translateX(-50%);
  }
}
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.review-track');
    if (!track) return;
    // Giữ tốc độ cố định: 28.8 px/s (= 1440px / 50s, tương ứng Home có 4 reviews)
    const pixelsPerSecond = 28.8;
    const halfWidth = track.scrollWidth / 2;
    track.style.animationDuration = (halfWidth / pixelsPerSecond).toFixed(1) + 's';
  });
</script>

<section class="ftco-section img" id="ftco-testimony" style="background-image: url(images/bg_1.jpg);"
  data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row justify-content-center mb-5">
      <div class="col-md-7 heading-section text-center ftco-animate">
        <span class="subheading">{{ __('messages.testimony') }}</span>
        <h2 class="mb-4">{{ __('messages.what_guests_say') }}</h2>
        <p>{{ __('messages.testimony_desc') }}</p>
      </div>
    </div>
  </div>
  <div class="review-scroll-wrapper">
    <div class="review-track">
      {{-- Bộ review gốc --}}
      @foreach ($reviews as $review)
      <div class="review-item">
        <div class="testimony">
          <blockquote>
            <p>&ldquo;{{ $review->review }}.&rdquo;</p>
          </blockquote>
          <div class="author d-flex">
            <div class="name align-self-center">{{ $review->name }}</div>
          </div>
        </div>
      </div>
      @endforeach

      {{-- Nhân đôi để tạo vòng lặp liền mạch --}}
      @foreach ($reviews as $review)
      <div class="review-item">
        <div class="testimony">
          <blockquote>
            <p>&ldquo;{{ $review->review }}.&rdquo;</p>
          </blockquote>
          <div class="author d-flex">
            <div class="name align-self-center">{{ $review->name }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
