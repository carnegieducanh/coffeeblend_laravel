@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col">
        <div class="container">
            @if(Session::has('success'))
                <p class="alert alert-success">{{ Session::get('success') }}</p>
            @endif
            @if(Session::has('error'))
                <p class="alert alert-danger">{{ Session::get('error') }}</p>
            @endif
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Quản lý Admin</h5>
                <a href="{{ route('create.admins') }}" class="btn btn-primary mb-4 float-right">Tạo Admin mới</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Email</th>
                            <th scope="col">Vai trò</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allAdmins as $admin)
                            <tr>
                                <th scope="row">{{ $admin->id }}</th>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if($admin->isSuperAdmin())
                                        <span class="badge badge-warning text-dark">Super Admin</span>
                                    @else
                                        <span class="badge badge-secondary">Admin</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$admin->isSuperAdmin())
                                        <a href="{{ route('delete.admin', $admin->id) }}"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Bạn có chắc muốn xóa admin này không?')">
                                            Xóa
                                        </a>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
