@extends('adminlte::page')

@section('title', 'Create User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('users.index') }}" class="btn btn-success">
        <i class="fa fa-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">User Details</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input  type="text"
                                        id="name"
                                        name="name"
                                        value="{{ $user->name }}"
                                        class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input  type="email"
                                        id="email"
                                        name="email"
                                        value="{{ $user->email }}"
                                        class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input  type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input  type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roles">Role</label>
                                <select id="roles"
                                        name="roles[]"
                                        multiple
                                        class="form-control roles @error('roles') is-invalid @enderror"
                                        style="width: 100%;">
                                    @foreach ($roles as $value => $label)
                                        <option value="{{ $value }}" {{ in_array($value,$userRoles) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div> <!-- /.row -->
                </div> <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Submit
                    </button>
                </div>
            </div> <!-- /.card -->
        </form>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
          <style>
        /* Selected item */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff;
            color: #fff;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            $('.roles').select2();
        });
    </script>
@endsection
