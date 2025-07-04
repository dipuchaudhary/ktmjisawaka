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
            <h2>Role Management</h2>
        </div>
    </div>
</div>
<div class="card mb-4">
                  <div class="card-header"><a class="btn btn-success float-right" href="{{ route('roles.create') }}"> Create Role</a></div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Name</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($roles as $index => $role)
                        <tr class="align-middle">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $role->name }}</td>
                          <td>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                <a class="btn btn-primary btn-sm" href="{{ route('roles.edit', $role->id) }}"><i class="fa-solid fas fa-edit"></i> Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fas fa-trash"></i> Delete</button>
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $roles->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                </div>
@stop
