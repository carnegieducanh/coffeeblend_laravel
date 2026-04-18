@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col">
        <div class="container">
            @if(Session::has('success'))
                <p class="alert alert-success">{{ Session::get('success') }}</p>
            @endif
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Customers</h5>
                <span class="text-muted ml-2">({{ $users->total() }} users)</span>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('edit.user', $user->id) }}"
                                       class="btn btn-sm btn-warning mr-1">Edit</a>
                                    <a href="{{ route('delete.user', $user->id) }}"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this user?')">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
