<div class="container">
  @if(Session::has('date'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('date') }}</p>
  @endif
</div>
<div class="container">
  @if(Session::has('booking'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('booking') }}</p>
  @endif
</div>
