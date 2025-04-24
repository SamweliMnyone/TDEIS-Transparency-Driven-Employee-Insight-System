<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="TDEIS">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('LOGO_3.png') }}">
    <!-- Preload critical CSS -->
    <link rel="preload" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('asset/css/style.css') }}" as="style">

    <!-- Async CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('asset/css/cs-skin-elastic.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}" media="print" onload="this.media='all'">

    <!-- Fallback CSS -->
    <noscript>
        <link rel="stylesheet" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/cs-skin-elastic.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    </noscript>

    <!-- Entrance Animation -->
    <style>
        #app-content {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        #app-content.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <!-- Page Content -->
    <div id="app-content">
        @yield('content')
    </div>

    <!-- Show content with animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('app-content').classList.add('show');
        });
    </script>

    <!-- Defer JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/popper.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.matchHeight.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/main.js') }}" defer></script>

    <!-- DNS Optimization -->
    <link rel="preconnect" href="{{ url('/') }}">
</body>
</html>
