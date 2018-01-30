@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="text-center mb-4">
    <h1>Register for {{ config('app.name') }}</h1>
</div>

<registration-form 
    :plans="{{ $plans }}" 
    :errors="{{ $errors }}"
    :old="{{ json_encode(session()->getOldInput()) }}"
    action="{{ route('register') }}"
></registration-form>

@endsection
