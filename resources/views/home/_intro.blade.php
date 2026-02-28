<section class="ftco-intro">
  <div class="container-wrap">
    <div class="wrap d-md-flex align-items-xl-end">
      @include('partials._info_bar')

      <div class="book p-4">
        <h3>Book a Table</h3>
        <form action="{{ route('booking.tables') }}" method="POST" class="appointment-form">
          @csrf
          <div class="d-md-flex">
            <div class="form-group">
              <input type="text" name="first_name" class="form-control" placeholder="First Name">
              @error('first_name')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group ml-md-4">
              <input type="text" name="last_name" class="form-control" placeholder="Last Name">
              @error('last_name')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="d-md-flex">
            <div class="form-group">
              <div class="input-wrap">
                <div class="icon"><span class="ion-md-calendar"></span></div>
                <input type="text" name="date" class="form-control appointment_date" placeholder="Date">
              </div>
              @error('date')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group ml-md-4">
              <div class="input-wrap">
                <div class="icon"><span class="ion-ios-clock"></span></div>
                <input type="text" name="time" class="form-control appointment_time" placeholder="Time">
              </div>
              @error('time')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror

              @if(isset(Auth::user()->id))
              <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
              @endif
            </div>
            <div class="form-group ml-md-4">
              <input name="phone" type="text" class="form-control" placeholder="Phone">
              @error('phone')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="d-md-flex">
            <div class="form-group">
              <textarea name="message" cols="30" rows="2" class="form-control" placeholder="Message"></textarea>
              @error('message')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group ml-md-4">
              <input type="submit" name="submit" value="Book" class="btn btn-white py-3 px-4">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>