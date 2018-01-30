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
                        <user-menu 
                            :user="{{ auth()->user() }}" 
                            on-trial="{{ auth()->user()->onGenericTrial() }}"
                        ></user-menu>
                    @endguest
                </div>
            </nav>
        </header>
        
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
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
