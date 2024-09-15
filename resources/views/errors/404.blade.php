<!-- resources/views/errors/404.blade.php -->

@extends('layout.app')

@section('content')
<div class="container text-center" style="margin-top: 100px">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <small>Sorry, the page you are looking for might have been removed or is temporarily unavailable.</p>
    <a href="{{ url('/') }}" class="btn btn-dark my-3">Go to Homepage</a>
</div>
@endsection
