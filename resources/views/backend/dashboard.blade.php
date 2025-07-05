@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $roles }}</h3>
                <p>Roles</p>
            </div>
            <div class="icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $users }}</h3>
                <p>Users</p>
            </div>
            <div class="icon">
                <i class="bi bi-person-plus"></i>
            </div>
            <a href="#" class="small-box-footer text-dark">
                More info <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

       <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $mull }}</h3>
                <p>मुल दर्ता</p>
            </div>
            <div class="icon">
                <i class="bi bi-book-half"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

           <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $banking }}</h3>
                <p>बैकिङ्ग दर्ता</p>
            </div>
            <div class="icon">
                <i class="bi bi-bank"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $challani }}</h3>
                <p>चलानी</p>
            </div>
            <div class="icon">
                <i class="bi bi-receipt"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>{{ $punarabedan }}</h3>
                <p>पुनरावेदन</p>
            </div>
            <div class="icon">
                <i class="bi bi-info-circle"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Bootstrap Icons CDN (if not included in your AdminLTE) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@stop

@section('js')
    {{-- Optional JavaScript here --}}
@stop
