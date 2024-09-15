@extends('layout.app')
@section('content')
@if (!$checkUtilityNeeded['status'])
    @include('layout.no-device-found', $checkUtilityNeeded)
@endif
@endsection
