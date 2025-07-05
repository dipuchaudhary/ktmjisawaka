@extends('adminlte::page')

@section('title', 'Create User')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 mt-3 mb-3">
        @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
@endsession
        <div class="pull-left">
            <h2>Users Management</h2>
        </div>
    </div>
</div>
<div class="card mb-4">
                @can('user-create')
                 <div class="card-header"> <a class="btn btn-success float-right" href="{{ route('users.create') }}"> Create User</a></div>
                 @endcan
                 <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Role</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @can('user-list')
                      <tbody>
                        @foreach ($users as $index => $user)
                        <tr class="align-middle">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->email }}</td>
                          <td>
                            @if (!empty($user->getRoleNames()))
                                @foreach ($user->getRoleNames() as $rolename)
                                    <label class="badge bg-primary mx-1">{{ $rolename }}</label>
                                @endforeach
                                @endif
                        </td>
                          <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @can('user-edit')
                                <a class="btn btn-primary btn-sm" href="{{ route('users.edit', $user->id) }}"><i class="fa-solid fas fa-edit"></i> Edit</a>
                                @endcan
                                @csrf
                                @method('DELETE')
                                @can('user-delete')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fas fa-trash"></i> Delete</button>
                                @endcan
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      @endcan
                    </table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                </div>
@stop
