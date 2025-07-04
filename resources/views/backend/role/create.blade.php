@extends('adminlte::page')

@section('title', 'Create Role')

@section('content_header')
    <h1>Create Role</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('roles.index') }}" class="btn btn-success">
        <i class="fa fa-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Role Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Role Name</label>
                        <input  type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <h5 class="mt-4 mb-3 d-flex align-items-center">
                        Assign Permissions
                        <span class="ml-auto">
                            {{-- Master toggle --}}
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="check-all">
                                <label for="check-all" class="text-primary font-weight-normal">
                                    Check All
                                </label>
                            </div>
                        </span>
                    </h5>
                    <div class="row">
                        @foreach($permissions->chunk(4) as $permission)
                            <div class="col-12 col-sm-6 col-md-3 mb-4">
                                <div class="p-2 bg-light rounded shadow-sm h-100">
                                    @foreach($permission as $perm)
                                        <div class="icheck-primary d-block mb-1">
                                            <input  type="checkbox"
                                                    id="{{ $perm->name }}"
                                                    name="permissions[]"
                                                    value="{{ $perm->name }}"
                                                    class="@error('permissions') is-invalid @enderror">
                                            <label for="{{ $perm->name }}">
                                                {{ ucwords(str_replace('-', ' ', $perm->name)) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <div class="text-danger mb-3">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Submit
                    </button>
                </div>
            </div><!-- /.card -->
        </form>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            $('.roles').select2();

            // --- Master "Check All" toggle ---
            $('#check-all').on('change', function () {
                const checked = this.checked;
                $('input[name="permissions[]"]').prop('checked', checked);
            });
        });
    </script>
@endsection
