@extends('projects.manage')

@section('title')
    {{ __('Suggested steps') }}
@endsection

@section('breadcrumbs')
    <li><a href="{{ localized_route('projects.manage', $project) }}">{{ $project->name }}</a></li>
@endsection

@section('content')
    <h2>{{ __('Suggested steps') }}</h2>
@endsection
