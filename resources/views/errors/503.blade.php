@extends('errors::errorpage')

@section('title', __('hearth::errors.error_503_title'))
@section('code', '503')
@section('message', __('hearth::errors.error_503_message'))
