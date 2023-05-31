@extends('errors::errorpage')

@section('title', __('hearth::errors.error_403_title'))
@section('code', '403')
@section('message')
    <p>{{ $exception->getMessage() }}</p>
    @include('partials.contact-information')
@endsection
