<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ env('APP_NAME') . '-' . Route::currentRouteName() }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700">
    <link rel="stylesheet" href="{{ asset('FrontEnd/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('FrontEnd/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('FrontEnd/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('FrontEnd/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('FrontEnd/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('FrontEnd/css/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="{{ asset('FrontEnd/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('FrontEnd/css/style.css') }}">
    @yield('header')

</head>

<body>

    <div class="site-wrap">
        <header class="site-navbar" role="banner">
            <div class="site-navbar-top">
                <div class="container">
                    <div class="row align-items-center">

                        <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
                            <form action="" class="site-block-top-search">
                                <span class="icon icon-search2"></span>
                                <input type="text" class="form-control border-0" placeholder="Search">
                            </form>
                        </div>

                        <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
                            <div class="site-logo">
                                <a href="{{ route('FrontEnd') }}" class="js-logo-clone">Ecommerce Site</a>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 order-3 order-md-3 text-right">
                            <div class="site-top-icons">
                                <div class="site-logo">
                                    <a href="{{ Route::currentRouteName() == 'login' ? route('register') : (Route::currentRouteName() == 'register' ? route('login') : route('login')) }}"
                                        class="js-logo-clone">
                                        {{ Route::currentRouteName() == 'login' ? 'Register' : (Route::currentRouteName() == 'register' ? 'Login' : 'Login') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container py-5">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
        @yield('footer')

</body>

</html>
