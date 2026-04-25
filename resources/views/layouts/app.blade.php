<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Laravel - @yield('title')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <ul>
                    <li>{{ $error }}</li>
                </ul>
            @endforeach        
        @endif

        @if (session()->has('status'))
            <div>{{ session()->get('status') }}</div>
        @endif

        <main>@yield('content')</main>

        @auth
            <script type="module">
                const id = "{{ auth()->user()->id }}";

                Echo.private(`App.Models.User.${id}`)
                    .notification(n => {
                        switch (n.type) {
                            case 'App\\Notifications\\Subscribed':
                                return console.log(n.user);
                            case 'App\\Notifications\\Published':
                                return console.log(n.post);
                        }
                    });
            </script>
        @endauth
    </body>
</html>