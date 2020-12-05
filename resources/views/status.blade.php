@extends('statamic::layout')
@section('title', __('Gitamic'))
{{--@section('wrapper_class', 'max-w-full')--}}

@section('content')

    <div class="flex mb-3">
        <h1 class="flex-1">{{ __('Gitamic') }}</h1>
        <button class="btn" @click.prevent="Statamic.$app.$refs.status.getStatus">{{ __('Refresh') }}</button>
    </div>

    <gitamic-status ref="status"></gitamic-status>

@endsection
