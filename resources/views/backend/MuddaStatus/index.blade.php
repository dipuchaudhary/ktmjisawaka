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
            <h2>Mudda Challani Status</h2>
        </div>
    </div>
</div>
<div class="row" id="search">
    <div class="col-lg-12 mt-3 mb-3">
        <form id="search-form" action="" method="POST" enctype="multipart/form-data">
            <div class="form-row d-flex">
                <div class="form-group col-md-4">
                    <select data-filter="make" class="filter-make filter form-control">
                        <option value="">मुडा श्रेणी छनौट गर्नुहोस् </option>
                        <option value="mudda_dartas">मुद्दा राय दर्ता</option>
                        <option value="banking_muddas">बैकिङ्ग राय दर्ता</option>
                        <option value="patra_challanis">पत्र चलानी</option>
                        <option value="aviyog_challanis">अभियोग चलानी</option>
                        <option value="punarabedans">पुनरावेदन</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <input class="form-control" type="text" placeholder="Search" />
                </div>
                <div class="form-group col-md-4">
                    <button type="submit" class="btn btn-block btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card mb-4">
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
                       {{-- @can('role-list')
                      <tbody>
                        @foreach ($roles as $index => $role)
                        <tr class="align-middle">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $role->name }}</td>
                          <td>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                @can('role-edit')
                                <a class="btn btn-primary btn-sm" href="{{ route('roles.edit', $role->id) }}"><i class="fa-solid fas fa-edit"></i> Edit</a>
                                @endcan
                                @csrf
                                @method('DELETE')
                                @can('role-delete')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fas fa-trash"></i> Delete</button>
                                 @endcan
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                       @endcan --}}
                    </table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer clearfix">
                    <div class="float-right">
                        {{-- {{ $roles->onEachSide(1)->links('pagination::bootstrap-4') }} --}}
                    </div>
                </div>
                </div>
@stop
