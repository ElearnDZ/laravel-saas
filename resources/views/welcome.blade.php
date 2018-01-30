@extends('layouts.app')
@section('view.class', 'flex flex-col items-center justify-center')

@section('content')
<div class="content">
    <div class="text-5xl mb-2">
        {{ config('app.name', 'Laravel for SaaS') }}
    </div>

    <div class="flex justify-between">
        <a href="https://laravel.com/docs">Documentation</a>
        <a href="https://laracasts.com">Laracasts</a>
        <a href="https://laravel-news.com">News</a>
        <a href="https://forge.laravel.com">Forge</a>
        <a href="https://github.com/laravel/laravel">GitHub</a>
    </div>
</div>
@endsection