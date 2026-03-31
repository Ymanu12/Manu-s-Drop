<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased" data-user-theme="{{ auth()->user()->theme ?? 'light' }}" data-theme-update-url="{{ route('user.theme.update') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">
        <script>
            (function () {
                var root = document.documentElement;
                var userTheme = root.dataset.userTheme || 'light';
                var savedTheme = localStorage.getItem('theme');
                var theme = savedTheme || userTheme || 'light';
                var isDark = theme === 'dark';

                root.classList.toggle('dark', isDark);
                root.setAttribute('data-theme', isDark ? 'dark' : 'light');
                root.style.colorScheme = isDark ? 'dark' : 'light';
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

    <body class="body bg-white text-slate-900 transition-colors dark:bg-slate-950 dark:text-slate-100">
        <style>
            :root {
                --theme-page-bg: rgb(255 255 255);
                --theme-surface: rgba(255, 255, 255, .92);
                --theme-surface-muted: rgba(248, 250, 252, .92);
                --theme-surface-strong: rgba(241, 245, 249, .92);
                --theme-border: rgba(148, 163, 184, .22);
                --theme-text: rgb(15 23 42);
                --theme-muted: rgb(100 116 139);
                --theme-accent: rgb(194 65 12);
                --theme-accent-soft: rgba(251, 146, 60, .14);
                --theme-shadow: 0 12px 36px rgba(15, 23, 42, .08);
            }

            .dark {
                --theme-page-bg: rgb(2 6 23);
                --theme-surface: rgba(15, 23, 42, .88);
                --theme-surface-muted: rgba(15, 23, 42, .78);
                --theme-surface-strong: rgba(30, 41, 59, .92);
                --theme-border: rgba(148, 163, 184, .18);
                --theme-text: rgb(248 250 252);
                --theme-muted: rgb(148 163 184);
                --theme-accent: rgb(251 146 60);
                --theme-accent-soft: rgba(251, 146, 60, .18);
                --theme-shadow: 0 18px 48px rgba(2, 6, 23, .32);
            }

            .section-menu-left,
            .header-dashboard,
            .main-content,
            .wg-box,
            .wg-chart-default,
            .dropdown-menu,
            .message-item,
            .user-item,
            .admin-kpi-card,
            .admin-list-item,
            .table-responsive,
            .form-search,
            .show-search,
            .select,
            input,
            textarea,
            select,
            button {
                transition: background-color .25s ease, border-color .25s ease, color .25s ease, box-shadow .25s ease;
            }

            .section-menu-left,
            .header-dashboard,
            .wg-box,
            .table-responsive,
            .dropdown-menu,
            .message-item,
            .user-item,
            .form-search,
            .show-search,
            .select,
            .admin-kpi-card,
            .admin-list-item,
            .wg-chart-default {
                background: var(--theme-surface) !important;
                border-color: var(--theme-border) !important;
                color: var(--theme-text) !important;
                box-shadow: var(--theme-shadow);
            }

            .main-content {
                background: var(--theme-surface-muted) !important;
                color: var(--theme-text) !important;
            }

            .center-heading,
            .menu-item .text,
            .sub-menu-item .text,
            .header-user .body-title,
            .body-title,
            .body-title-2,
            .header-item,
            .admin-shell-text,
            .bottom-page .body-text,
            .dropdown-menu h6,
            .table thead th,
            .table tbody td,
            .table tbody td a,
            .main-content h1,
            .main-content h2,
            .main-content h3,
            .main-content h4,
            .main-content h5,
            .main-content h6,
            .main-content p,
            .main-content label,
            .main-content span {
                color: var(--theme-text) !important;
            }

            .text-tiny,
            .admin-shell-muted,
            .bottom-page .body-text,
            .main-content .text-secondary {
                color: var(--theme-muted) !important;
            }

            .show-search,
            .form-search input,
            .main-content input,
            .main-content textarea,
            .main-content select {
                background: var(--theme-surface-muted) !important;
                border-color: var(--theme-border) !important;
                color: var(--theme-text) !important;
            }

            .show-search::placeholder,
            .form-search input::placeholder,
            .main-content input::placeholder,
            .main-content textarea::placeholder {
                color: var(--theme-muted) !important;
            }

            .message-item:hover,
            .user-item:hover,
            .menu-item > a:hover,
            .sub-menu-item a:hover {
                background: var(--theme-accent-soft) !important;
                color: var(--theme-text) !important;
            }

            .logout-button-reset {
                width: 100%;
                border: 0;
                background: transparent;
                text-align: left;
                padding: 0;
            }


            .admin-shell-card {
                background: var(--theme-surface) !important;
                border: 1px solid var(--theme-border) !important;
                border-radius: 1rem;
                box-shadow: var(--theme-shadow);
            }

            .admin-shell-search {
                background: var(--theme-surface-muted) !important;
                border: 1px solid var(--theme-border) !important;
                border-radius: .875rem;
                padding: .5rem;
                box-shadow: none;
            }

            .admin-shell-search .button-submit button,
            .admin-shell-search .icon-search {
                color: var(--theme-text) !important;
            }

            .admin-shell-table {
                background: var(--theme-surface-muted) !important;
                border: 1px solid var(--theme-border) !important;
                border-radius: 1rem;
                box-shadow: var(--theme-shadow);
                overflow: hidden;
            }

            .admin-shell-table .table {
                margin-bottom: 0;
            }

            .admin-shell-table .table thead th {
                background: var(--theme-surface-strong) !important;
                color: var(--theme-text) !important;
                border-color: var(--theme-border) !important;
            }

            .admin-shell-table .table tbody td,
            .admin-shell-table .table tbody th {
                background: transparent !important;
                border-color: var(--theme-border) !important;
                color: var(--theme-text) !important;
            }

            .admin-shell-table .table-striped > tbody > tr:nth-of-type(odd),
            .admin-shell-table .table-striped > tbody > tr:nth-of-type(even) {
                --bs-table-accent-bg: transparent !important;
            }

            .admin-shell-input,
            .admin-shell-input.flex-grow,
            .admin-shell-textarea,
            .admin-shell-select,
            .admin-shell-select select {
                background: var(--theme-surface-muted) !important;
                border-color: var(--theme-border) !important;
                color: var(--theme-text) !important;
                border-radius: .75rem;
            }

            .admin-shell-input::placeholder,
            .admin-shell-textarea::placeholder {
                color: var(--theme-muted) !important;
            }

            .admin-shell-select select {
                background: transparent !important;
                box-shadow: none !important;
            }

            .admin-shell-danger-action {
                border: none;
                background: transparent;
                color: rgb(220 38 38) !important;
                cursor: pointer;
                padding: 0;
            }

            .admin-inline-hidden {
                display: none;
            }

            .admin-preview-image {
                max-width: 150px;
                border-radius: .875rem;
                border: 1px solid var(--theme-border);
                box-shadow: var(--theme-shadow);
            }

            .admin-preview-image--wide {
                max-width: 300px;
            }

            .upload-image .item,
            #galUpload .gitems,
            #imgpreview {
                border-radius: 1rem;
            }

            .upload-image .item.up-load,
            #galUpload.item.up-load {
                background: var(--theme-surface-muted);
                border: 1px dashed var(--theme-border);
            }

            .uploadfile .body-text,
            .uploadfile .text-tiny,
            .uploadfile .icon,
            .uploadfile .tf-color {
                color: var(--theme-text) !important;
            }

            .admin-shell-section-gap {
                margin-top: 2rem;
            }


            .admin-col-order {
                width: 70px;
            }

            .main-content .badge.bg-success {
                background: rgba(34, 197, 94, .18) !important;
                border: 1px solid rgba(34, 197, 94, .28);
                color: rgb(21, 128, 61) !important;
            }

            .main-content .badge.bg-danger {
                background: rgba(239, 68, 68, .16) !important;
                border: 1px solid rgba(239, 68, 68, .28);
                color: rgb(185, 28, 28) !important;
            }

            .main-content .badge.bg-warning {
                background: rgba(245, 158, 11, .18) !important;
                border: 1px solid rgba(245, 158, 11, .28);
                color: rgb(146, 64, 14) !important;
            }

            .dark .main-content .badge.bg-success {
                color: rgb(134, 239, 172) !important;
            }

            .dark .main-content .badge.bg-danger {
                color: rgb(252, 165, 165) !important;
            }

            .dark .main-content .badge.bg-warning {
                color: rgb(253, 230, 138) !important;
            }
            .dark .section-content-right,
            .dark #wrapper,
            .dark #page,
            .dark .layout-wrap,
            .dark .bottom-page {
                background: var(--theme-page-bg) !important;
                color: var(--theme-text) !important;
            }

            .button-show-hide,
            .button-show-hide i,
            .menu-item .icon,
            .menu-item .icon i,
            .sub-menu-item .icon,
            .sub-menu-item i,
            .header-grid .dropdown-toggle,
            .header-grid .dropdown-toggle i,
            .header-grid .header-item,
            .header-grid .header-item i,
            .header-user .image,
            .header-user .image img,
            .popup-wrap .icon,
            .popup-wrap .icon i,
            .popup-wrap .number,
            .theme-toggle,
            .theme-toggle svg,
            .bottom-page,
            .bottom-page .body-text {
                color: var(--theme-text) !important;
                border-color: var(--theme-border) !important;
            }

            .button-show-hide,
            .header-grid .dropdown-toggle,
            .theme-toggle,
            .popup-wrap .number {
                background: var(--theme-surface) !important;
                border: 1px solid var(--theme-border) !important;
                box-shadow: var(--theme-shadow);
            }

            .header-grid .dropdown-toggle:hover,
            .theme-toggle:hover,
            .button-show-hide:hover,
            .menu-item > a:hover,
            .menu-item-button:hover,
            .sub-menu-item a:hover,
            .popup-wrap .dropdown-menu .user-item:hover,
            .popup-wrap .dropdown-menu .message-item:hover {
                background: var(--theme-accent-soft) !important;
                color: var(--theme-text) !important;
            }

            .menu-item > a,
            .menu-item-button,
            .sub-menu-item a,
            .popup-wrap .dropdown-menu .user-item,
            .popup-wrap .dropdown-menu .message-item,
            .popup-wrap .dropdown-menu .body-title-2,
            .popup-wrap .dropdown-menu .text-tiny {
                color: var(--theme-text) !important;
            }

            .popup-wrap .dropdown-menu,
            .popup-wrap .dropdown-menu .user-item,
            .popup-wrap .dropdown-menu .message-item,
            .header-user,
            .header-item {
                background: var(--theme-surface) !important;
                color: var(--theme-text) !important;
                border-color: var(--theme-border) !important;
            }

            .popup-wrap .dropdown-menu .text-tiny,
            .header-user .text-tiny,
            .bottom-page .body-text {
                color: var(--theme-muted) !important;
            }

            .popup-wrap .number {
                color: var(--theme-accent) !important;
                border-radius: 9999px;
                min-width: 1.75rem;
                text-align: center;
            }

            .dark .header-user .image img {
                border: 1px solid var(--theme-border);
            }

            .dark .menu-item.active > a,
            .dark .menu-item.active .menu-item-button,
            .dark .sub-menu-item.active a {
                background: var(--theme-accent-soft) !important;
                color: var(--theme-text) !important;
            }

            .dark .theme-toggle__icon-light,
            .theme-toggle__icon-dark {
                display: none;
            }

            .dark .theme-toggle__icon-dark,
            .theme-toggle__icon-light {
                display: block;
            }

            .dark .header-grid .dropdown-toggle::after {
                filter: invert(1);
                opacity: .8;
            }

            .dark .bottom-page {
                border-top: 1px solid var(--theme-border);
            }

            .dark .bottom-page .body-text {
                color: var(--theme-muted) !important;
            }
            .section-menu-left > .box-logo,
            .header-dashboard .wrap .header-left > a,
            .header-dashboard .wrap .header-left,
            .header-dashboard .wrap .form-search,
            .header-dashboard .wrap .header-left .box-content-search {
                background: var(--theme-surface) !important;
                border-color: var(--theme-border) !important;
                color: var(--theme-text) !important;
            }

            .section-menu-left > .box-logo,
            .header-dashboard .wrap .header-left > a {
                box-shadow: var(--theme-shadow);
            }

            .section-menu-left > .box-logo {
                border-bottom: 1px solid var(--theme-border) !important;
            }

            .header-dashboard .wrap .header-left > a {
                border: 1px solid var(--theme-border);
                border-radius: .875rem;
                padding: .55rem .9rem;
            }

            .header-dashboard .wrap .header-left > a img,
            .section-menu-left > .box-logo img {
                filter: none;
            }

            .header-dashboard .wrap .form-search {
                border: 1px solid var(--theme-border) !important;
                border-radius: .95rem;
                padding: .25rem;
                box-shadow: var(--theme-shadow);
            }

            .header-dashboard .wrap .form-search fieldset,
            .header-dashboard .wrap .form-search .show-search,
            .header-dashboard .wrap .form-search input {
                background: transparent !important;
                color: var(--theme-text) !important;
            }

            .header-dashboard .wrap .form-search .show-search,
            .header-dashboard .wrap .form-search input {
                border: 0 !important;
                box-shadow: none !important;
            }

            .header-dashboard .wrap .form-search .show-search::placeholder,
            .header-dashboard .wrap .form-search input::placeholder {
                color: var(--theme-muted) !important;
                opacity: 1;
            }

            .header-dashboard .wrap .form-search .button-submit button,
            .header-dashboard .wrap .form-search .button-submit i,
            .header-dashboard .wrap .header-left .button-show-hide,
            .header-dashboard .wrap .header-left .button-show-hide i,
            .section-menu-left .button-show-hide,
            .section-menu-left .button-show-hide i {
                color: var(--theme-text) !important;
            }

            .header-dashboard .wrap .header-left .box-content-search {
                border: 1px solid var(--theme-border) !important;
                border-radius: 1rem;
                box-shadow: var(--theme-shadow) !important;
            }

            .header-dashboard .wrap .header-left .box-content-search .product-item,
            .header-dashboard .wrap .header-left .box-content-search .product-item .name a,
            .header-dashboard .wrap .header-left .box-content-search .product-item .price {
                color: var(--theme-text) !important;
            }

            .dark .section-menu-left > .box-logo,
            .dark .header-dashboard .wrap .header-left > a,
            .dark .header-dashboard .wrap .form-search,
            .dark .header-dashboard .wrap .header-left .box-content-search {
                background: var(--theme-surface) !important;
                border-color: var(--theme-border) !important;
            }
        
            .main-content-inner {
                width: 100%;
            }

            .main-content-wrap {
                width: min(1400px, 90%);
                max-width: 1400px;
                margin: 0 auto;
                padding-inline: 0;
            }

            .admin-page-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1.25rem;
                flex-wrap: wrap;
                margin-bottom: 2rem;
                padding: 1.75rem 2rem;
                border: 1px solid var(--theme-border);
                border-radius: 1.25rem;
                background: linear-gradient(135deg, var(--theme-surface) 0%, var(--theme-surface-muted) 100%);
                box-shadow: var(--theme-shadow);
            }

            .admin-page-header__eyebrow {
                color: var(--theme-accent) !important;
                font-size: .82rem;
                font-weight: 700;
                letter-spacing: .08em;
                text-transform: uppercase;
                margin-bottom: .4rem;
            }

            .admin-page-header__title {
                margin: 0;
                font-size: 2rem;
                line-height: 1.15;
            }

            .admin-page-header__meta {
                color: var(--theme-muted) !important;
                max-width: 62rem;
                margin-top: .5rem;
                font-size: 1.02rem;
                line-height: 1.65;
            }

            .admin-breadcrumbs {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .admin-form-shell,
            .admin-form-panel,
            .admin-dashboard-hero {
                width: 100%;
                margin-inline: 0;
                background: linear-gradient(180deg, var(--theme-surface) 0%, var(--theme-surface-muted) 100%) !important;
                border: 1px solid var(--theme-border) !important;
                border-radius: 1.25rem;
                box-shadow: var(--theme-shadow);
            }

            .admin-dashboard-hero {
                display: grid;
                grid-template-columns: minmax(0, 1.4fr) minmax(280px, .8fr);
                gap: 1.5rem;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .admin-dashboard-hero__lede {
                color: var(--theme-muted) !important;
                max-width: 46rem;
                margin-top: .5rem;
            }

            .admin-dashboard-hero__stats {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .9rem;
            }

            .admin-dashboard-mini {
                border: 1px solid var(--theme-border);
                border-radius: 1rem;
                padding: 1rem;
                background: rgba(255,255,255,.38);
            }

            .dark .admin-dashboard-mini {
                background: rgba(15, 23, 42, .42);
            }

            .admin-dashboard-mini__label {
                color: var(--theme-muted) !important;
                font-size: .78rem;
                margin-bottom: .35rem;
            }

            .admin-dashboard-mini__value {
                color: var(--theme-text) !important;
                font-size: 1.35rem;
                font-weight: 700;
            }

            .admin-form-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 2rem;
                align-items: start;
            }

            .admin-form-grid > *,
            .admin-form-grid--two-up > * {
                grid-column: span 1;
                min-width: 0;
            }

            .admin-form-stack {
                display: flex;
                flex-direction: column;
                gap: 2rem;
                width: 100%;
            }

            .admin-form-panel {
                padding: 2rem 2.25rem;
            }

            .admin-form-panel__header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: nowrap;
                margin-bottom: 1.5rem;
            }

            .admin-form-panel__title {
                margin: 0;
                font-size: 1.45rem;
                font-weight: 800;
                color: var(--theme-text) !important;
                line-height: 1.2;
            }

            .admin-form-panel__meta {
                color: var(--theme-muted) !important;
                margin-top: .45rem;
                font-size: 1rem;
                line-height: 1.65;
                max-width: 60rem;
            }

            .admin-form-badge {
                display: inline-flex;
                align-items: center;
                gap: .4rem;
                padding: .6rem .95rem;
                border-radius: 999px;
                background: var(--theme-accent-soft);
                color: var(--theme-accent) !important;
                font-size: .9rem;
                font-weight: 700;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .form-new-product,
            .form-style-1,
            .form-add-product,
            .my-account__edit-form form {
                display: flex;
                flex-direction: column;
                gap: 0;
                width: 100%;
            }

            .theme-form-field,
            .form-new-product fieldset,
            .form-style-1 fieldset,
            .form-add-product fieldset,
            .my-account__edit-form fieldset {
                margin: 0 0 2rem;
                padding: 1.5rem 1.6rem;
                border: 1px solid var(--theme-border);
                border-radius: 1rem;
                background: rgba(255,255,255,.42);
                width: 100%;
            }

            .dark .theme-form-field,
            .dark .form-new-product fieldset,
            .dark .form-style-1 fieldset,
            .dark .form-add-product fieldset,
            .dark .my-account__edit-form fieldset {
                background: rgba(15, 23, 42, .4);
            }

            .form-new-product fieldset .body-title,
            .form-style-1 fieldset .body-title,
            .form-add-product fieldset .body-title,
            .my-account__edit-form fieldset .body-title,
            .theme-form-field .body-title {
                margin-bottom: .9rem !important;
                font-size: 1.1rem;
                font-weight: 700;
                line-height: 1.35;
            }

            .theme-form-field.form-floating {
                position: relative;
                margin-bottom: 2rem;
            }

            .theme-form-field > label {
                color: var(--theme-muted) !important;
                font-size: 1.1rem;
                padding: 1rem 1.25rem;
                height: auto;
                line-height: 1.2;
                pointer-events: none;
            }

            .theme-form-field > .theme-form-control,
            .theme-form-field > .form-control {
                padding: 1rem 1.25rem !important;
            }

            .theme-form-field > textarea.theme-form-control,
            .theme-form-field > textarea.form-control {
                min-height: 14rem;
                padding-top: 1.75rem !important;
            }

            .theme-form-field > .form-control:focus ~ label,
            .theme-form-field > .form-control:not(:placeholder-shown) ~ label,
            .theme-form-field > textarea.form-control:focus ~ label,
            .theme-form-field > textarea.form-control:not(:placeholder-shown) ~ label,
            .theme-form-field > .theme-form-control:focus ~ label,
            .theme-form-field > .theme-form-control:not(:placeholder-shown) ~ label {
                transform: scale(.82) translateY(-.9rem) translateX(.15rem);
                color: var(--theme-accent) !important;
            }
            .form-new-product input,
            .form-new-product textarea,
            .form-new-product select,
            .form-style-1 input,
            .form-style-1 textarea,
            .form-style-1 select,
            .form-add-product input,
            .form-add-product textarea,
            .form-add-product select,
            .my-account__edit-form input,
            .my-account__edit-form textarea,
            .my-account__edit-form select {
                width: 100%;
                min-height: 3.8rem;
                font-size: 1.1rem;
                line-height: 1.5;
                border-radius: .95rem;
                border: 1px solid var(--theme-border) !important;
                background: var(--theme-surface-muted) !important;
                color: var(--theme-text) !important;
                padding: 1rem 1.25rem !important;
                box-shadow: none !important;
            }

            .form-new-product input::placeholder,
            .form-new-product textarea::placeholder,
            .form-style-1 input::placeholder,
            .form-style-1 textarea::placeholder,
            .form-add-product input::placeholder,
            .form-add-product textarea::placeholder,
            .my-account__edit-form input::placeholder,
            .my-account__edit-form textarea::placeholder {
                font-size: 1.1rem;
                color: var(--theme-muted) !important;
            }

            .form-new-product textarea,
            .form-style-1 textarea,
            .form-add-product textarea,
            .my-account__edit-form textarea {
                min-height: 12rem;
                resize: vertical;
            }

            .form-new-product input:focus,
            .form-new-product textarea:focus,
            .form-new-product select:focus,
            .form-style-1 input:focus,
            .form-style-1 textarea:focus,
            .form-style-1 select:focus,
            .form-add-product input:focus,
            .form-add-product textarea:focus,
            .form-add-product select:focus,
            .my-account__edit-form input:focus,
            .my-account__edit-form textarea:focus,
            .my-account__edit-form select:focus {
                border-color: rgba(251, 146, 60, .45) !important;
                box-shadow: 0 0 0 .22rem rgba(251, 146, 60, .12) !important;
            }

            .form-new-product .select,
            .form-style-1 .select,
            .form-add-product .select,
            .my-account__edit-form .select {
                width: 100%;
                border-radius: .95rem;
                border: 1px solid var(--theme-border) !important;
                background: var(--theme-surface-muted) !important;
                padding: 0 .25rem;
            }

            .admin-form-help,
            .form-new-product .text-tiny,
            .form-style-1 .text-tiny,
            .form-add-product .text-tiny,
            .my-account__edit-form .text-tiny {
                color: var(--theme-muted) !important;
                margin-top: .7rem;
                display: block;
                line-height: 1.55;
                font-size: 1rem;
            }

            .admin-form-actions,
            .form-new-product .bot,
            .form-style-1 .bot,
            .form-add-product .bot {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 1rem;
                margin-top: 0;
                width: 100%;
            }

            .admin-form-actions--split {
                justify-content: space-between;
            }

            .admin-form-actions .tf-button,
            .form-new-product .tf-button,
            .form-style-1 .tf-button,
            .form-add-product .tf-button,
            .my-account__edit-form .tf-button,
            .my-account__edit-form .btn-primary {
                min-height: 3.2rem;
                border-radius: .95rem;
                padding: .95rem 1.4rem;
                box-shadow: 0 14px 28px rgba(194, 65, 12, .16);
            }

            .admin-form-note {
                display: inline-flex;
                align-items: center;
                gap: .45rem;
                color: var(--theme-muted) !important;
                font-size: 1rem;
            }

            .admin-form-alert {
                border: 1px solid var(--theme-border);
                border-radius: 1rem;
                padding: 1rem 1.1rem;
                margin: 0;
            }

            .admin-form-alert ul {
                margin: 0;
                padding-left: 1rem;
            }

            .admin-upload-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 1.5rem;
            }

            .admin-upload-grid > * {
                min-width: 0;
            }

            .admin-shell-card .upload-image .item,
            .admin-shell-card #galUpload .gitems {
                border-radius: 1rem;
                overflow: hidden;
            }

            @media (max-width: 1199px) {
                .admin-dashboard-hero {
                    grid-template-columns: 1fr;
                }

                .main-content-wrap {
                    width: min(100%, calc(100% - 2rem));
                    max-width: none;
                }
            }

            @media (max-width: 767px) {
                .admin-page-header,
                .admin-dashboard-hero,
                .admin-form-panel {
                    padding: 1.2rem;
                }

                .admin-page-header__title {
                    font-size: 1.6rem;
                }

                .admin-dashboard-hero__stats,
                .admin-upload-grid {
                    grid-template-columns: 1fr;
                }

                .admin-form-panel__header,
                .admin-form-actions,
                .admin-form-actions--split {
                    flex-direction: column;
                    align-items: stretch;
                }
            }        </style>
            <div id="wrapper">
                <div id="page" class="">
                    <div class="layout-wrap">

                        <!-- <div id="preload" class="preload-container">
            <div class="preloading">
                <span></span>
            </div>
        </div> -->

                        <div class="section-menu-left border-r border-slate-200/70 bg-white/90 transition-colors dark:border-slate-800 dark:bg-slate-950/90">
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

                            <div class="header-dashboard border-b border-slate-200/70 bg-white/90 transition-colors dark:border-slate-800 dark:bg-slate-950/90">
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
                                        <button type="button" class="theme-toggle rounded-full border border-slate-200 bg-white/80 text-slate-900 shadow-sm backdrop-blur transition hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-100" data-theme-toggle aria-label="Toggle theme" aria-pressed="false">
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
                                                            <button type="submit" class="user-item logout-button-reset">
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
                            <div class="main-content transition-colors">

                                @yield('content')

                                <div class="bottom-page">
                                    <div class="body-text">Copyright &copy; 2024 Manu's Shop</div>
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

    $("#search-input").on("keydown", function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    $("#search-input").on("keyup", function() {
        var searchQuery = $(this).val().trim();
        var resultBox = $("#box-content-search");

        resultBox.html("");

        if (searchQuery.length <= 2) {
            resultBox.append(`
                <li class="admin-search-empty">
                    <div class="body-title-2">Start typing</div>
                    <div class="admin-search-empty__meta">Enter at least 3 characters to search for a product.</div>
                </li>
            `);
            return;
        }

        $.ajax({
            type: "GET",
            url: "{{ route('admin.search') }}",
            data: { query: searchQuery },
            dataType: "json",
            success: function(data) {
                resultBox.html("");

                if (!data || data.length === 0) {
                    resultBox.append(`
                        <li class="admin-search-empty">
                            <div class="body-title-2">No products found</div>
                            <div class="admin-search-empty__meta">No results for \"${searchQuery}\".</div>
                        </li>
                    `);
                    return;
                }

                $.each(data, function(index, item) {
                    var url = "{{ route('admin.product.edit', ['id' => 'product_id']) }}";
                    var link = url.replace('product_id', item.id);

                    resultBox.append(`
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
                    `);
                });
            },
            error: function() {
                resultBox.html(`
                    <li class="admin-search-empty">
                        <div class="body-title-2">Search unavailable</div>
                        <div class="admin-search-empty__meta">Unable to load results right now.</div>
                    </li>
                `);
            }
        });
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


