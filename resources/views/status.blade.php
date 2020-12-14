@extends('statamic::layout')
@section('title', __('Gitamic'))
{{--@section('wrapper_class', 'max-w-full')--}}

@section('content')

    <gitamic-status ref="status"></gitamic-status>

@endsection
