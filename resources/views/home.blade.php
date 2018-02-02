@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="container mx-auto">
    @if (session('status'))
        <div class="alert alert-success mx-auto mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if (!auth()->user()->confirmed)
        <div class="alert alert-danger mx-auto mb-4 self-start">
            We've sent you an email to confirm your email address. 
            <a class="text-white hover:text-white hover:no-underline underline" href="{{ route('confirm.resend') }}">Click here to resend the confirmation mail</a>
        </div>
    @endif

    <div class="text-3xl leading-normal">
        Your amazing app goes here.<br>
        I can't help you with that. 🤓
    </div>
</div>
@endsection
