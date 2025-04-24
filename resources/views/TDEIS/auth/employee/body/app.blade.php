<!doctype html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Transparency-Driven Employee Insight System">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('LOGO_3.png') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('templates/assets/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">

    <!-- Bubble Loader Style -->
    <style>
        .loader-wrapper {
            display: none;
            justify-content: center;
            align-items: center;
            position: absolute;
            z-index: 1000;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
        }
        .loader {
            display: flex;
            gap: 0.5rem;
        }
        .loader div {
            width: 10px;
            height: 10px;
            background-color: #3498db;
            border-radius: 50%;
            animation: loader-bounce 0.6s infinite alternate;
        }
        .loader div:nth-child(2) {
            animation-delay: 0.2s;
        }
        .loader div:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes loader-bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }
    </style>
</head>

<body>
    @include('TDEIS.auth.employee.body.sidebar')
    @include('TDEIS.auth.employee.body.nav')

    <!-- Loader -->
    <div class="loader-wrapper" id="page-loader">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <!-- Page content -->
    <div class="page-content" id="dynamic-content">
        @yield('content')
    </div>

    @include('TDEIS.auth.employee.body.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="{{ asset('templates/assets/js/main.js')}}"></script>

    <script>
    $(document).ready(function () {
        // Intercept all anchor clicks with .ajax-link class
        $(document).on('click', 'a.ajax-link', function (e) {
            e.preventDefault();

            const url = $(this).attr('href');

            $('#page-loader').fadeIn();

            const loaderMinTime = 15000; // Minimum time to show loader in ms
            const startTime = new Date().getTime();

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    const content = $(data).find('#dynamic-content').html();
                    const elapsedTime = new Date().getTime() - startTime;
                    const delay = Math.max(0, loaderMinTime - elapsedTime);

                    setTimeout(function () {
                        $('#dynamic-content').html(content);
                        $('#page-loader').fadeOut();
                        window.history.pushState(null, '', url);
                    }, delay);
                },
                error: function () {
                    $('#page-loader').fadeOut();
                    alert('Failed to load content.');
                }
            });
        });

        // Handle browser back/forward
        window.onpopstate = function () {
            location.reload();
        };
    });
</script>

</body>
</html>
