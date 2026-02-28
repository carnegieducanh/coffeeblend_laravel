<section class="ftco-intro">
  <div class="container-wrap">
    <div class="wrap d-md-flex align-items-xl-end">
      @include('partials._info_bar')

      <div class="book p-4">
        <h3>{{ __('messages.book_a_table') }}</h3>
        <form action="{{ route('booking.tables') }}" method="POST" class="appointment-form">
          @csrf
          <div class="d-md-flex">
            <div class="form-group">
              @auth
              <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" readonly>
              @else
              <input type="text" name="name" class="form-control" placeholder="{{ __('messages.placeholder_name') }}">
              @endauth
              @error('name')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="d-md-flex">
            <div class="form-group">
              <div class="input-wrap">
                <div class="icon"><span class="ion-md-calendar"></span></div>
                <input type="text" name="date" class="form-control appointment_date" placeholder="{{ __('messages.placeholder_date') }}">
              </div>
              @error('date')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group ml-md-4">
              <div class="input-wrap">
                <div class="icon"><span class="ion-ios-clock"></span></div>
                <input type="text" name="time" class="form-control appointment_time" placeholder="{{ __('messages.placeholder_time') }}">
              </div>
              @error('time')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror

              @if(isset(Auth::user()->id))
              <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
              @endif
            </div>
            <div class="form-group ml-md-4">
              <input name="phone" type="text" class="form-control" placeholder="{{ __('messages.placeholder_phone') }}">
              @error('phone')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="d-md-flex">
            <div class="form-group">
              <textarea name="message" cols="30" rows="2" class="form-control" placeholder="{{ __('messages.placeholder_message') }}"></textarea>
              @error('message')
              <span class="booking-field-error">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group ml-md-4">
              <input type="submit" name="submit" value="{{ __('messages.btn_book') }}" class="btn book-btn py-3 px-4">
            </div>
          </div>
        </form>
        <style>
        .book-btn {
          background: #000 !important;
          color: #fff !important;
          border: 1px solid #000 !important;
          transition: background 0.2s, color 0.2s;
        }

        .book-btn:hover {
          background: whitesmoke !important;
          /* Màu xám Bootstrap */
          color: black !important;
          border-color: #6c757d !important;
        }

        /* Datepicker: ngày trong quá khứ làm mờ */
        .datepicker table tr td.disabled,
        .datepicker table tr td.disabled:hover {
          color: #ccc !important;
          opacity: 0.4;
          cursor: not-allowed;
          font-weight: normal !important;
        }

        /* Datepicker: ngày từ hôm nay trở đi in đậm */
        .datepicker table tr td.day:not(.disabled) {
          font-weight: 700;
          color: #1d150b;
        }

        /* Ngày hôm nay highlight thêm màu amber */
        .datepicker table tr td.today:not(.active) {
          background: #fff3e0 !important;
          border-color: #c49b63 !important;
          color: #c49b63 !important;
          font-weight: 700;
        }
        </style>
      </div>
    </div>
  </div>
</section>