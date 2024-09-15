@extends('layout.app')
@section('content')
@if (!$checkUtilityNeeded['status'])
    @include('layout.no-device-found', $checkUtilityNeeded)
@else
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Maintenance!</strong>
            <small>This page is under maintenance</small>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card">
            <div class="card-body">
                @include('app.user.device.includes.form')
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="card">
            <div class="card-body">
                @include('app.user.device.includes.table')
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    
        $("#dataTable").DataTable()
    });
    </script>
@endsection