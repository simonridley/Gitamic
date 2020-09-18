@extends('statamic::layout')
@section('title', __('Gitamic'))
{{--@section('wrapper_class', 'max-w-full')--}}

@section('content')

    <div class="flex mb-3">
        <h1 class="flex-1">{{ __('Gitamic') }}</h1>
        <a href="" class="btn" @click.prevent="this.$refs.status.getStatus">{{ __('Refresh') }}</a>
    </div>

    <gitamic-status ref="status"></gitamic-status>

@endsection
