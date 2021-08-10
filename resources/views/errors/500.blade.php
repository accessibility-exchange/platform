@extends('errors::errorpage')

@section('title', __('hearth::errors.error_500_title'))
@section('code', '500')
@section('message', __('hearth::errors.error_500_message'))
