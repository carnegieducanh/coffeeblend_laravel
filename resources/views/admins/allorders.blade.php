@extends('layouts.admin')


@section('content')
<div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
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
          <h5 class="card-title mb-4 d-inline">Orders</h5>
        
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">first_name</th>
                <th scope="col">last_name</th>
                <th scope="col">city</th>
                <th scope="col">state</th>
                <th scope="col">phone</th>
                <th scope="col">street_address</th>
                <th scope="col">total_price</th>
                <th scope="col">status</th>
                <th scope="col">change status</th>
                <th scope="col">delete</th>
              </tr>
            </thead>
            <tbody>

                @foreach ($allOrders as $order)
                 <tr>
                    <th scope="row">{{ $order->id }}</th>
                    <td>{{ $order->first_name }}</td>
                    <td>{{ $order->last_name }}</td>
                    <td>{{ $order->city }}</td>
                    <td>{{ $order->state }}</td>
                   
                    <td>{{ $order->phone }}</td>
                    <td>
                        {{ $order->address }}
                    </td>
                    <td>${{ $order->price }}</td>
    
                    <td>{{ $order->status }}</td>
                    <td><a href="{{ route('edit.order', $order->id) }}" class="btn btn-warning  text-white text-center ">change status</a></td>

                    <td><a href="{{ route('delete.order', $order->id) }}" class="btn btn-danger  text-center ">delete</a></td>
                  </tr>
                @endforeach
          
           
            </tbody>
          </table> 
        </div>
      </div>
    </div>
  </div>

  @endsection