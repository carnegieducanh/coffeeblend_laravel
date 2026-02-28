@php
$flashMessages = [
    'success'        => 'alert-success',
    'booking'        => 'alert-success',
    'reviews'        => 'alert-success',
    'update'         => 'alert-info',
    'delete'         => 'alert-warning',
    'login_required' => 'alert-warning',
    'error'          => 'alert-danger',
    'date'           => 'alert-danger',
];
@endphp

@foreach($flashMessages as $key => $class)
  @if(Session::has($key))
  <div class="flash-alert alert {{ $class }} alert-dismissible fade show mb-0" role="alert">
    {{ Session::get($key) }}
    <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
@endforeach
