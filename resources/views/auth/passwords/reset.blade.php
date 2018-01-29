@extends('layouts.app')
@section('view.class', 'center-all')


@section('content')
<div class="text-center mb-4">
    <h1>Set a new password</h1>
</div>

<form class="w-full max-w-sm rounded shadow-md p-6 mx-auto" method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="form-label">E-Mail Address</label>

        <div class="col-md-6">
            <input id="email" type="email" name="email" value="{{ $email or old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="alert alert-danger">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="form-label">Password</label>

        <div class="col-md-6">
            <input id="password" type="password" name="password" required>

            @if ($errors->has('password'))
                <span class="alert alert-danger">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label for="password-confirm" class="form-label">Confirm Password</label>
        <div class="col-md-6">
            <input id="password-confirm" type="password" name="password_confirmation" required>

            @if ($errors->has('password_confirmation'))
                <span class="alert alert-danger">
                    {{ $errors->first('password_confirmation') }}
                </span>
            @endif
        </div>
    </div>

    <div class="mt-6 flex items-center justify-end">
        <button type="submit" class="btn">
            Reset password
        </button>
    </div>
</form>
@endsection
