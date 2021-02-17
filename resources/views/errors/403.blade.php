@extends('errors::errorpage')

@section('title', __('errors.error_403_title'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'errors.error_403_message'))
