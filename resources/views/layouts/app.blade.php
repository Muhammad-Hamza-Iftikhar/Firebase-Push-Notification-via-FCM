<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('{{ asset('firebase-messaging-sw.js') }}')
    .then(function(registration) {
      console.log('Service Worker Registered:', registration);
      // Pass the endpoint URL to the Service Worker during registration
      registration.active.postMessage({ endpointUrl: '{{ route('store.notification') }}' });
      console.log('{{ route('store.notification') }}');
    })
    .catch(function(err) {
      console.log('Service Worker registration failed: ', err);
    });
}

        var firebaseConfig = {
            apiKey: "AIzaSyD11X1uPuGInvHF8NAgMWCjfeLXakZl088"
            , authDomain: "laravel-notifications-805c5.firebaseapp.com"
            , projectId: "laravel-notifications-805c5"
            , storageBucket: "laravel-notifications-805c5.appspot.com"
            , messagingSenderId: "7976724441"
            , appId: "1:7976724441:web:f1b094f737c78816c31bc8"
            , measurementId: "G-B5Z1XVCYQG"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body
                , icon: payload.notification.icon
            , };
            const notify = new Notification(noteTitle, noteOptions);
            if (payload.data.user_id == {{auth()->id()}}) {
                console.log('Notification Recived.');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
