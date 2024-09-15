<!-- resources/views/errors/500.blade.php -->

@extends('layout.app')

@section('content')
<div class="container text-center" style="margin-top: 100px">
    <h1>500</h1>
    <h2>Internal Server Error</h2>
    <p>Something went wrong on our end. Please try again later.</p>
    <a href="{{ url('/') }}" class="btn btn-dark btn-sm my-3">Go to Homepage</a>
</div>
@endsection
