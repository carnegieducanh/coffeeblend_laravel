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

<section class="ftco-section img" id="ftco-testimony" style="background-image: url(images/bg_1.jpg);"
  data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row justify-content-center mb-5">
      <div class="col-md-7 heading-section text-center ftco-animate">
        <span class="subheading">Testimony</span>
        <h2 class="mb-4">Customers Says</h2>
        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind
          texts.</p>
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
          <div class="author d-flex mt-4">
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
          <div class="author d-flex mt-4">
            <div class="name align-self-center">{{ $review->name }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>