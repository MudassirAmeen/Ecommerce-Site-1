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
                                <a href="{{ route('home') }}" class="js-logo-clone">Ecommerce Site</a>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 order-3 order-md-3 text-right">
                            <div class="site-top-icons">
                                <div class="site-logo">
                                    {{--  <a href="#" class="js-logo-clone has-children">Welcome {{auth()->user()->name}}</a>  --}}
                                    <nav class="site-navigation text-right text-md-center" role="navigation">
                                        <ul class="site-menu js-clone-nav d-none d-md-block">
                                            <li class="has-children js-logo-clone has-children">
                                                <a href="{{ route('home') }}">{{ auth()->user()->name }}</a>
                                                <ul class="dropdown">
                                                    <li>
                                                        <a href="#" class="w-100">
                                                            <form action="{{ route('logout') }}" method="POST"
                                                                style="display:contents">
                                                                @csrf
                                                                <button type="submit"
                                                                    style="display:contents; cursor: pointer;">Loguot</button>
                                                            </form>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <nav class="site-navigation text-right text-md-center" role="navigation">
                <div class="container">
                    <ul class="site-menu js-clone-nav d-none d-md-block">
                        <li class="{{ Route::currentRouteNamed('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="has-children {{ Route::currentRouteNamed('product.index') ? 'active' : '' }}">
                            <a href="{{ route('product.index') }}">Products</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('product.create') }}">Add Product</a></li>
                            </ul>
                        </li>
                        <li class="has-children {{ Route::currentRouteNamed('category.index') ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}">Categoies</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('category.create') }}">Add Category</a></li>
                            </ul>
                        </li>
                        <li class="has-children {{ Route::currentRouteNamed('coupons.index') ? 'active' : '' }}">
                            <a href="{{ route('coupons.index') }}">Coupons</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('coupons.create') }}">Add Coupon</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (View::hasSection('main'))
                @yield('main')
            @else
            <div class="card">
                <div class="card-body">
                  <h2>Welcome to our platform!</h2>
                  <p>Thank you for signing up. You can now enjoy all the features and benefits of our platform.</p>
                </div>
              </div>
            @endif

        </div>
        @yield('footer')

</body>

</html>
