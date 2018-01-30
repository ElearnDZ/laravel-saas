@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="container mx-auto">
    @if (session('status'))
        <div class="alert alert-success mx-auto mb-4">
            {{ session('status') }}
        </div>
    @endif

    <div class="text-3xl leading-normal">
        Your amazing app goes here.<br>
        I can't help you with that. 🤓
    </div>
</div>
@endsection
