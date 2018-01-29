@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="text-center mb-4">
    <h1>Register for {{ config('app.name') }}</h1>
</div>

<form class="w-full max-w-sm rounded shadow-md p-6 mx-auto" method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="form-label">Name</label>

        <div>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="alert alert-danger">
                    {{ $errors->first('name') }}
                </span>
            @endif
        </div>
    </div>

    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="form-label">E-Mail Address</label>

        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <span class="alert alert-danger">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>
    </div>

    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="form-label">Password</label>

        <div>
            <input id="password" type="password" name="password" required>

            @if ($errors->has('password'))
                <span class="alert alert-danger">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>
    </div>

    <div>
        <label for="password-confirm" class="form-label">Confirm Password</label>

        <div>
            <input id="password-confirm" type="password" name="password_confirmation" required>
        </div>
    </div>


    <div class="mt-6 flex items-center justify-end">
        <button type="submit" class="btn">
            Register
        </button>
    </div>
</form>
@endsection
