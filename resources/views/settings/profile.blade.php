@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="text-center mb-4">
    <h1>Update your profile</h1>
</div>

<form class="formbox" action="{{ route('settings.profile') }}" method="POST">
    {{ method_field('PATCH') }}
    {{ csrf_field() }}
    
    <div class="{{ $errors->has('name') ? 'has-error' : '' }} mb-4">
        <label for="name" class="form-label">Name</label>

        <div>
            <input id="name" type="text" name="name" value="{{ auth()->user()->name }}">
        
            @if ($errors->has('name'))
                <span class="alert alert-danger">
                    {{ $errors->first('name') }}
                </span>
            @endif         
        </div>
    </div>

    <div class="{{ $errors->has('email') ? 'has-error' : '' }} mb-4">
        <label for="email" class="form-label">E-Mail Address</label>

        <div>
            <input id="email" type="email" name="email" value="{{ auth()->user()->email }}">
       
            @if ($errors->has('email'))
                <span class="alert alert-danger">
                    {{ $errors->first('email') }}
                </span>
            @endif        
        </div>
    </div>

    <div class="{{ $errors->has('password') ? 'has-error' : '' }} mb-4">
        <label for="password" class="form-label">Change password</label>

        <div>
            <input id="password" type="password" name="password">

            @if ($errors->has('password'))
                <span class="alert alert-danger">
                    {{ $errors->first('password') }}
                </span>
            @endif            
        </div>
    </div>

    <div class="mb-4">
        <label for="password-confirm" class="form-label">Confirm new password</label>

        <div>
            <input id="password-confirm" type="password" name="password_confirmation">
        </div>
    </div>

    <div class="mb-4">
        <p class="text-sm text-grey-dark my-0">Hint: We get your avatar from Gravatar! To update it, please go to gravatar.com and change the settings in your Gravatar account.</p>
    </div>

    <div class="mt-6 flex items-center justify-end">
        <button type="submit" class="btn">
            Update profile
        </button>
    </div>

</form>
@endsection