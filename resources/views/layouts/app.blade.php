<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="stripe-token" content="{{ config('services.stripe.key') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
    window.Configuration = {
        csrfToken: document.head.querySelector('meta[name="csrf-token"]').content,
        stripeToken: document.head.querySelector('meta[name="stripe-token"]').content,
    }

    @if (auth()->user())
        window.User = {!! auth()->user() !!}
        window.User.address = {!! auth()->user()->address !!}
        window.User.subscription = {!! 
            auth()->user()->subscription('main')
            ? auth()->user()->subscription('main')
            : "null"
        !!}

        window.User.onTrial = {!! auth()->user()->onGenericTrial() ? "true" : "false" !!}
        window.User.pastTrial = {!! auth()->user()->isPastTrial() && !auth()->user()->subscription('main') ? "true" : "false" !!}
        window.User.onGracePeriod = {!!
            auth()->user()->subscription('main')
            ? json_encode(auth()->user()->subscription('main')->onGracePeriod())
            : "false"
        !!}
    @else
        window.User = null
    @endif
    </script>
</head>
<body class="min-h-screen">
    <div id="app" class="min-h-screen flex-grow flex flex-col">
        <header class="container mx-auto mt-6">
            <nav class="border-bottom flex items-center justify-between">
                <div>
                    <!-- Branding Image -->
                    <a href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div>
                    <!-- Authentication Links -->
                    @guest
                        <a class="mr-4" href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @else
                        <user-menu></user-menu>
                    @endguest
                </div>
            </nav>
        </header>

        <div class="container mx-auto mt-4">
            @if (session('status'))
                <div class="alert alert-success mx-auto mb-4 w-100">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mx-auto mb-4 w-100">
                    {{ session('error') }}
                </div>
            @endif

            @if (auth()->user() && !auth()->user()->confirmed)
                <div class="alert alert-danger mx-auto mb-4 self-start">
                    We've sent you an email to confirm your email address. 
                    <a class="text-white hover:text-white hover:no-underline underline" href="{{ route('confirm.resend') }}">Click here to resend the confirmation mail</a>
                </div>
            @endif

            @if (auth()->user() && !auth()->user()->subscription('main') && auth()->user()->isPastTrial())
                <div class="alert alert-danger mx-auto mb-4 self-start">
                    Your trial has expired. Please update your billing settings to continue using our service.
                </div>
            @endif
        </div>
        
        <main class="flex-auto @yield('view.class')">
            @yield('content')
        </main>

        <footer class="container mx-auto mb-6">
            <div class="text-grey">
                Copyright &copy; {{ date('Y') }}  {{ config('app.name') }}
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
