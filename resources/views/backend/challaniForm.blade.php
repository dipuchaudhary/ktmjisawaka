@extends('adminlte::page')

@section('title', 'Challani')

@section('content_header')
@stop

@section('content')
        <div class="row">
            <div class="col-md-6">
             <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Challani Number Format</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{ route('challani.store') }}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="format_prefix">Challani Format</label>
                    <input type="text" class="form-control" id="format_prefix" name="format_prefix" placeholder="Enter challani format" value="{{ $format->format_prefix }}">
                  </div>
                  <button type="submit" class="btn btn-primary">Save</button>
              </form>
            </div>
        </div>
@stop
