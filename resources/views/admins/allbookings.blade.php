@extends('layouts.admin')


@section('content')

<div class="row">
    <div class="col">
        <div class="container">
            @if(Session::has('update'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('update') }}</p>
            @endif
          </div>
          <div class="container">
            @if(Session::has('delete'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('delete') }}</p>
            @endif
          </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Bookings</h5>
        
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">first_name</th>
                <th scope="col">last_name</th>
                <th scope="col">date</th>
                <th scope="col">time</th>
                <th scope="col">phone</th>
                <th scope="col">message</th>
                <th scope="col">status</th>
                <th scope="col">change stauts</th>

                <th scope="col">created_at</th>
                <th scope="col">delete</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                <tr>
                    <th scope="row">{{ $booking->id }}</th>
                    <td>{{ $booking->first_name }}</td>
                    <td>{{ $booking->last_name }}</td>
                    <td>{{ $booking->date }} </td>
                    <td>{{ $booking->time }}</td>
                    <td>{{ $booking->phone }}</td>
                    <td>
                        {{ $booking->message }}
                    </td>
                    <td>{{ $booking->status }}</td>
                    <td><a href="{{ route('edit.booking', $booking->id) }}" class="btn btn-warning  text-white text-center ">change stauts</a></td>

                    <td>{{ $booking->created_at }}</td>


                     <td><a href="{{ route('delete.booking', $booking->id) }}" class="btn btn-danger  text-center ">delete</a></td>
                  </tr>
                @endforeach
            
             
            </tbody>
          </table> 
        </div>
      </div>
    </div>
  </div>
@endsection