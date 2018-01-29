@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="text-center mb-4">
    <h1>Login to {{ config('app.name') }}</h1>
</div>

<form class="w-full max-w-sm rounded shadow-md p-6 mx-auto" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <div class="{{ $errors->has('email') ? 'has-error' : '' }}">
        <label class="form-label" for="email">E-Mail Address</label>

        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="alert alert-danger">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>
    </div>

    <div class="{{ $errors->has('password') ? 'has-error' : '' }}">
        <label class="form-label" for="password">Password</label>

        <div>
            <input id="password" type="password" name="password" required>

            @if ($errors->has('password'))
                <span class="alert alert-danger">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>
    </div>

    <div class="mt-6 flex items-center justify-between">
        <label class="checkbox-label">
            <input class="mr-1" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
        </label>

        <button type="submit" class="btn">
            Login
        </button>
    </div>
</form>

<div class="mt-6 text-center">
    <a class="text-sm text-grey hover:text-blue no-underline" href="{{ route('password.request') }}">
        Forgot your password?
    </a>
</div>
@endsection
