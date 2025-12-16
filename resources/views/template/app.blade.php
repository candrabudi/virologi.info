@php
    use App\Models\Website;
    use App\Models\FooterSetting;
    use App\Models\FooterQuickLink;
    use App\Models\FooterContact;

    $setting = Website::first();
    $footerSetting = FooterSetting::where('is_active', true)->first();
    $quickLinks = FooterQuickLink::where('is_active', true)->orderBy('sort_order')->get();
    $contacts = FooterContact::where('is_active', true)->orderBy('sort_order')->get();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/fav.png') }}"> --}}
    <title>@yield('title') | {{ $setting ? $setting->name : 'Default' }}</title>

    <link rel="stylesheet preload" href="{{ asset('assets/css/plugins/fontawesome.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('assets/css/plugins/swiper.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('assets/css/plugins/metismenu.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('assets/css/plugins/magnifying-popup.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('assets/css/plugins/odometer.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" as="style">

    <link rel="preconnect" href="{{ asset('fonts.googleapis.com/index.html') }}">
    <link rel="preconnect" href="{{ asset('fonts.gstatic.com/index.html') }}" crossorigin>
    <link
        href="{{ asset('fonts.googleapis.com/css2fadf.css') }}?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet preload" as="style">

    <link rel="preload" as="image" href="{{ asset('assets/images/banner/21.webp') }}">
    <link rel="stylesheet preload" href="{{ asset('assets/css/style.css') }}" as="style">
</head>

<body>

    @include('template.header')
    @yield('content')

    <div class="footer-8-area-bg bg_image pt--65">
        <div class="container pb--65">
            <div class="row">

                <div class="col-lg-3">
                    <div class="footer-logo-area-left-8">
                        <a href="/" class="logo">
                            <img src="{{ asset($footerSetting->logo_path ?? 'logo-2.png') }}" alt="Footer Logo">
                        </a>
                        <p class="disc">
                            {{ $footerSetting->description ?? '' }}
                        </p>
                    </div>
                </div>

                <div class="offset-lg-1 col-lg-4">
                    <div class="footer-one-single-wized">
                        <div class="wized-title">
                            <h5 class="title">Quick Links</h5>
                            <img src="{{ asset('assets/images/footer/under-title.png') }}" alt="">
                        </div>

                        <div class="quick-link-inner">
                            <ul class="links">
                                @foreach ($quickLinks as $link)
                                    <li>
                                        <a href="{{ $link->url }}">
                                            <i class="far fa-arrow-right"></i> {{ $link->label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="offset-lg-1 col-lg-3">
                    <div class="footer-one-single-wized">
                        <div class="wized-title">
                            <h5 class="title">Contact Us</h5>
                            <img src="{{ asset('assets/images/footer/under-title.png') }}" alt="">
                        </div>

                        <div class="quick-link-inner d-block">
                            @foreach ($contacts as $contact)
                                <div class="signle-footer-contact-8">
                                    <div class="icon">
                                        @if ($contact->type === 'email')
                                            <i class="fa-solid fa-envelope"></i>
                                        @elseif ($contact->type === 'phone')
                                            <i class="fa-solid fa-phone"></i>
                                        @elseif ($contact->type === 'address')
                                            <i class="fa-solid fa-location-dot"></i>
                                        @else
                                            <i class="fa-solid fa-circle-info"></i>
                                        @endif
                                    </div>

                                    <div class="inner-content">
                                        <h5 class="title">{{ $contact->label }}</h5>

                                        @if ($contact->type === 'email')
                                            <a href="mailto:{{ $contact->value }}">{{ $contact->value }}</a>
                                        @elseif ($contact->type === 'phone')
                                            <a href="tel:{{ $contact->value }}">{{ $contact->value }}</a>
                                        @else
                                            <span>{{ $contact->value }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="copyright-area-main-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-8-wrapper">
                            <p>{{ $footerSetting->copyright_text ?? '' }}</p>
                            <ul>
                                <li><a href="#">Copyright</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Cookies Setting</a></li>
                                <li><a href="#">Get Latest News</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="side-bar" class="side-bar header-two">
        <button class="close-icon-menu" title="Close menu">
            <i class="far fa-times"></i>
        </button>

        <div class="rts-sidebar-menu-desktop">
            <a class="logo-1" href="/">
                <img class="logo" src="{{ asset('logo.png') }}" alt="virologi_logo">
            </a>

            <div class="body d-none d-xl-block">
                <p class="disc">Make Better World Through Cyber and Technology</p>

                <div class="get-in-touch">
                    <div class="h6 title">Contact Us</div>

                    <div class="wrapper">
                        <div class="single">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:overlord@virologi.info">overlord@virologi.info</a>
                        </div>
                    </div>

                    <div class="social-wrapper-two menu">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-menu d-block d-xl-none">
            <nav class="nav-main mainmenu-nav mt--30">
                <ul class="mainmenu metismenu" id="mobile-menu-active">
                    <li><a href="/" class="main">Beranda</a></li>
                    <li><a href="/tentang-kami" class="main">Tentang Kami</a></li>
                    <li><a href="/produk" class="main">Produk</a></li>
                    <li><a href="/artikel" class="main">Artikel</a></li>
                    <li><a href="/agent-ai" class="main">Agent AI</a></li>
                    <li><a href="mailto:overlord@virologi.info" class="main">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="search-input-area">
        <div class="container">
            <div class="search-input-inner">
                <div class="input-div">
                    <input class="search-input autocomplete" type="text" placeholder="Search by keyword or #">
                    <button><i class="far fa-search"></i></button>
                </div>
            </div>
        </div>
        <div id="close" class="search-close-icon"><i class="far fa-times"></i></div>
    </div>

    <div id="anywhere-home"></div>

    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <script defer src="{{ asset('assets/js/plugins/jquery.js') }}">
        < /cript> <
        script defer src = "{{ asset('assets/js/plugins/odometer.js') }}" >
    </script>
    <script defer src="{{ asset('assets/js/plugins/jquery-appear.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/gsap.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/split-text.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/scroll-trigger.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/smooth-scroll.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/metismenu.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/popup.js') }}"></script>
    <script defer src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/swiper.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/contact.form.js') }}"></script>
    <script defer src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>
