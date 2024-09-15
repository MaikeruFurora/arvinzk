<!-- resources/views/errors/419.blade.php -->

@extends('layout.app')

@section('content')
<div class="container text-center" style="margin-top: 100px">
    <h1>419</h1>
    <h2>Page Expired</h2>
    <small>Your session has expired due to inactivity. Please refresh the page and try again.</small>
    <a href="{{ url('/') }}" class="btn btn-dark my-3 btn-sm">Click here</a>
</div>
@endsection
