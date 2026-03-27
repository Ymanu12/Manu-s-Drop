<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-user-theme="{{ auth()->user()->theme ?? 'light' }}" data-theme-update-url="{{ route('user.theme.update') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">
        <script>
            (function () {
                var userTheme = document.documentElement.dataset.userTheme || 'light';
                var savedTheme = localStorage.getItem('theme');
                document.documentElement.setAttribute('data-theme', savedTheme || userTheme || 'light');
            })();
        </script>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <!-- <meta name="author" content="surfside media" /> -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">
        <link rel="stylesheet" href="{{ asset('icon/style.css') }}">
        <!-- <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}"> -->
        <link rel="apple-touch-icon-precomposed" href="{{ asset('images/favicon.ico') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

        @stack("styles")
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>

    <body class="body">
            <div id="wrapper">
                <div id="page" class="">
                    <div class="layout-wrap">

                        <!-- <div id="preload" class="preload-container">
            <div class="preloading">
                <span></span>
            </div>
        </div> -->

                        <div class="section-menu-left">
                            <div class="box-logo">
                                <a href="{{route('admin.index')}}" id="site-logo-inner">
                                    <img class="" id="logo_header" alt="Manu's Drop" src="{{ asset('favicon.ico') }}"
                                        data-light="{{ asset('favicon.ico') }}" data-dark="{{ asset('favicon.ico') }}">
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>
                            </div>
                            <div class="center">
                                <div class="center-item">
                                    <div class="center-heading">Main Home</div>
                                    <ul class="menu-list">
                                        <li class="menu-item">
                                            <a href="{{route('admin.index')}}" class="">
                                                <div class="icon"><i class="icon-grid"></i></div>
                                                <div class="text">Dashboard</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="center-item">
                                    <ul class="menu-list">
                                        <li class="menu-item has-children">
                                            <a href="javascript:void(0);" class="menu-item-button">
                                                <div class="icon"><i class="icon-shopping-cart"></i></div>
                                                <div class="text">Products</div>
                                            </a>
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a href="{{route('admin.product.add')}}" class="">
                                                        <div class="text">Add Product</div>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a href="{{route('admin.products')}}" class="">
                                                        <div class="text">Products</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-item has-children">
                                            <a href="javascript:void(0);" class="menu-item-button">
                                                <div class="icon"><i class="icon-layers"></i></div>
                                                <div class="text">Brand</div>
                                            </a>
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a href="{{route('admin.brand.add')}}" class="">
                                                        <div class="text">New Brand</div>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a href="{{route('admin.brands')}}" class="">
                                                        <div class="text">Brands</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-item has-children">
                                            <a href="javascript:void(0);" class="menu-item-button">
                                                <div class="icon"><i class="icon-layers"></i></div>
                                                <div class="text">Category</div>
                                            </a>
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a href="{{route('admin.category.add')}}" class="">
                                                        <div class="text">New Category</div>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a href="{{route('admin.categories')}}" class="">
                                                        <div class="text">Categories</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="menu-item has-children">
                                            <a href="javascript:void(0);" class="menu-item-button">
                                                <div class="icon"><i class="icon-file-plus"></i></div>
                                                <div class="text">Order</div>
                                            </a>
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a href="{{route('admin.orders')}}" class="">
                                                        <div class="text">Orders</div>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a href="order-tracking.html" class="">
                                                        <div class="text">Order tracking</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{route('admin.sliders')}}" class="">
                                                <div class="icon"><i class="icon-image"></i></div>
                                                <div class="text">Slides</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{route('admin.coupons')}}" class="">
                                                <div class="icon"><i class="icon-grid"></i></div>
                                                <div class="text">Coupons</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{route('admin.contacts')}}" class="">
                                                <div class="icon"><i class="icon-mail"></i></div>
                                                <div class="text">Message</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="users.html" class="">
                                                <div class="icon"><i class="icon-user"></i></div>
                                                <div class="text">User</div>
                                            </a>
                                        </li>

                                        <li class="menu-item">
                                            <a href="{{route('admin.account.edit')}}" class="">
                                                <div class="icon"><i class="icon-settings"></i></div>
                                                <div class="text">Settings</div>
                                            </a>
                                        </li>

                                        <li class="menu-item">
                                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                                @csrf
                                                <a href="#" class="menu-link menu-link_us-s" 
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <div class="icon"><i class="icon-log-out"></i></div>
                                                    <div class="text">Logout</div>
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="section-content-right">

                            <div class="header-dashboard">
                                <div class="wrap">
                                    <div class="header-left">
                                        <a href="index-2.html">
                                            <img class="" id="logo_header_mobile" alt="Manu's Drop" src="{{ asset('favicon.ico') }}"
                                                data-light="{{ asset('favicon.ico') }}" data-dark="{{ asset('favicon.ico') }}"
                                                data-width="154px" data-height="52px" data-retina="{{ asset('favicon.ico') }}">
                                        </a>
                                        <div class="button-show-hide">
                                            <i class="icon-menu-left"></i>
                                        </div>


                                        <form class="form-search flex-grow">
                                            <fieldset class="name">
                                                <input type="text" placeholder="Search here..." class="show-search" id="search-input" name="name" autocomplete="search"
                                                    tabindex="2" value="" aria-required="true" required="">
                                            </fieldset>
                                            <div class="button-submit">
                                                <button class="" type="submit"><i class="icon-search"></i></button>
                                            </div>
                                            <div class="box-content-search">
                                                <ul id="box-content-search">

                                                </ul>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="header-grid">
                                        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle color theme">
                                            <svg class="theme-toggle__icon-light" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 3V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M12 18.5V21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M3 12H5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M18.5 12H21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M5.64 5.64L7.41 7.41" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M16.59 16.59L18.36 18.36" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M16.59 7.41L18.36 5.64" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M5.64 18.36L7.41 16.59" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
                                            </svg>
                                            <svg class="theme-toggle__icon-dark" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 15.5A8.5 8.5 0 0 1 8.5 4 9 9 0 1 0 20 15.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                            </svg>
                                        </button>

                                        <div class="popup-wrap message type-header">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="header-item">
                                                        <span class="text-tiny">{{ $adminHeaderData['notificationCount'] ?? 0 }}</span>
                                                        <i class="icon-bell"></i>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end has-content"
                                                    aria-labelledby="dropdownMenuButton2">
                                                    <li>
                                                        <h6>Notifications</h6>
                                                    </li>
                                                    @forelse(($adminHeaderData['notifications'] ?? collect()) as $notification)
                                                        <li>
                                                            <a href="{{ $notification['url'] }}" class="message-item item-1">
                                                                <div class="image">
                                                                    <i class="{{ $notification['icon'] }}"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="body-title-2">{{ $notification['title'] }}</div>
                                                                    <div class="text-tiny">{{ $notification['message'] }}</div>
                                                                    <div class="text-tiny mt-4">{{ $notification['time'] }}</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @empty
                                                        <li>
                                                            <div class="message-item item-1">
                                                                <div class="image">
                                                                    <i class="icon-bell"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="body-title-2">No new notifications</div>
                                                                    <div class="text-tiny">New orders, contact messages, and low stock alerts will appear here.</div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforelse
                                                    <li><a href="{{ route('admin.orders') }}" class="tf-button w-full">View orders</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="popup-wrap user type-header">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="header-user wg-user">
                                                        <span class="image">
                                                            <img src="{{ asset('images/avatar/user-1.png') }}" alt="">
                                                        </span>
                                                        <span class="flex flex-column">
                                                            <span class="body-title mb-2">{{ auth()->user()->name ?? 'Administrator' }}</span>
                                                            <span class="text-tiny">{{ auth()->user()->email ?? 'Admin' }}</span>
                                                        </span>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end has-content"
                                                    aria-labelledby="dropdownMenuButton3">
                                                    <li>
                                                        <a href="{{ route('admin.account.edit') }}" class="user-item">
                                                            <div class="icon">
                                                                <i class="icon-user"></i>
                                                            </div>
                                                            <div class="body-title-2">Admin account</div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.orders') }}" class="user-item">
                                                            <div class="icon">
                                                                <i class="icon-file-text"></i>
                                                            </div>
                                                            <div class="body-title-2">Orders</div>
                                                            <div class="number">{{ $adminHeaderData['notificationCount'] ?? 0 }}</div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.contacts') }}" class="user-item">
                                                            <div class="icon">
                                                                <i class="icon-mail"></i>
                                                            </div>
                                                            <div class="body-title-2">Messages</div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('home.index') }}" class="user-item">
                                                            <div class="icon">
                                                                <i class="icon-grid"></i>
                                                            </div>
                                                            <div class="body-title-2">View store</div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('logout') }}">
                                                            @csrf
                                                            <button type="submit" class="user-item" style="width: 100%; border: 0; background: transparent; text-align: left;">
                                                                <div class="icon">
                                                                    <i class="icon-log-out"></i>
                                                                </div>
                                                                <div class="body-title-2">Log out</div>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="main-content">

                                @yield('content')

                                <div class="bottom-page">
                                    <div class="body-text">Copyright © 2024 Manu's Shop</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script src="{{ asset('assets/js/plugins/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>   
            <script src="{{ asset('js/sweetalert.min.js') }}"></script>    
            <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
            <script src="{{ asset('js/main.js') }}"></script>

            <script>
                $(document).ready(function() {

                    // Bloquer la soumission du formulaire si on appuie sur Entrée
                    $("#search-input").on("keydown", function(e) {
                        if (e.keyCode === 13) {
                            e.preventDefault(); // Empêche d'envoyer le formulaire
                        }
                    });

                    // Faire la recherche Ajax normalement
                    $("#search-input").on("keyup", function() {
                        var searchQuery = $(this).val();
                        if (searchQuery.length > 2) {
                            $.ajax({
                                type: "GET",
                                url: "{{ route('admin.search') }}",
                                data: { query: searchQuery },
                                dataType: "json",
                                success: function(data) {
                                    $("#box-content-search").html(""); // Nettoie avant de rajouter

                                    $.each(data, function(index, item) {
                                        var url = "{{ route('admin.product.edit', ['id' => 'product_id']) }}";
                                        var link = url.replace('product_id', item.id); // ATTENTION c'est item.slug PAS limace

                                        $("#box-content-search").append(`
                                            <ul>
                                                <li class="product-item gap14 mb-10">
                                                    <div class="image no-bg">
                                                        <img src="/uploads/products/thumbnails/${item.image}" alt="${item.name}">
                                                    </div>
                                                    <div class="flex items-center justify-between gap20 flex-grow">
                                                        <div class="name">
                                                            <a href="${link}" class="body-text">${item.name}</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        `);
                                    });
                                }
                            });
                        }
                    });

                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const dropdownButtons = [
                        document.getElementById('dropdownMenuButton2'),
                        document.getElementById('dropdownMenuButton3')
                    ].filter(Boolean);

                    const closeHeaderDropdowns = function () {
                        dropdownButtons.forEach(function (button) {
                            const dropdown = button.closest('.dropdown');
                            const menu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;

                            button.classList.remove('show');
                            button.setAttribute('aria-expanded', 'false');

                            if (menu) {
                                menu.classList.remove('show');
                            }
                        });
                    };

                    dropdownButtons.forEach(function (button) {
                        button.addEventListener('click', function (event) {
                            event.preventDefault();
                            event.stopPropagation();

                            const dropdown = button.closest('.dropdown');
                            const menu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;
                            const isOpen = menu ? menu.classList.contains('show') : false;

                            closeHeaderDropdowns();

                            if (!isOpen && menu) {
                                button.classList.add('show');
                                button.setAttribute('aria-expanded', 'true');
                                menu.classList.add('show');
                            }
                        });
                    });

                    document.querySelectorAll('.header-grid .dropdown-menu').forEach(function (menu) {
                        menu.addEventListener('click', function (event) {
                            event.stopPropagation();
                        });
                    });

                    document.addEventListener('click', function () {
                        closeHeaderDropdowns();
                    });

                    document.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape') {
                            closeHeaderDropdowns();
                        }
                    });
                });
            </script>

            @stack("scripts") 
        </body>  
</html>
