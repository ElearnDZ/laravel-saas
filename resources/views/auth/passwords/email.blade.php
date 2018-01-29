@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="text-center mb-4">
    <h1>Reset your password</h1>
</div>

@if (session('status'))
    <div class="w-full max-w-sm mx-auto mb-4 alert alert-success">
        {{ session('status') }}
    </div>
@endif

<form class="w-full max-w-sm rounded shadow-md p-6 mx-auto" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

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

    <div class="mt-6 flex items-center justify-end">
        <button type="submit" class="btn">
            Send Password Reset Link
        </button>
    </div>
</form>
@endsection
