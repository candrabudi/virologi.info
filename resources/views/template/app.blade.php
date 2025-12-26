<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') | {{ $setting?->name ?? 'Default' }}</title>

    {{-- CSS PLUGINS (kecil, langsung load) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/metismenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/magnifying-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/odometer.css') }}">

    {{-- BOOTSTRAP (preload BENAR) --}}
    <link rel="preload" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" as="style" onload="this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
    </noscript>

    {{-- MAIN STYLE --}}
    <link rel="preload" href="{{ asset('assets/css/style.css') }}" as="style" onload="this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    </noscript>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..1000&family=Red+Hat+Display:wght@300..900&display=swap"
        rel="stylesheet">

    {{-- HERO IMAGE --}}
    <link rel="preload" as="image" href="{{ asset('assets/images/banner/21.webp') }}">
</head>

<body>

    @include('template.header')
    @yield('content')

    {{-- FOOTER --}}
    <div class="footer-8-area-bg bg_image pt--65">
        <div class="container pb--65">
            <div class="row">

                <div class="col-lg-3">
                    <div class="footer-logo-area-left-8">
                        <a href="/" class="logo">
                            <img src="{{ asset($footerSetting?->logo_path ?? 'logo-2.png') }}" alt="Footer Logo">
                        </a>
                        <p class="disc">
                            {{ $footerSetting?->description ?? '' }}
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
                                @forelse ($quickLinks ?? [] as $link)
                                    <li>
                                        <a href="{{ $link->url }}">
                                            <i class="far fa-arrow-right"></i> {{ $link->label }}
                                        </a>
                                    </li>
                                @empty
                                    <li>-</li>
                                @endforelse
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
                            @forelse ($contacts ?? [] as $contact)
                                <div class="signle-footer-contact-8">
                                    <div class="icon">
                                        @switch($contact->type)
                                            @case('email') <i class="fa-solid fa-envelope"></i> @break
                                            @case('phone') <i class="fa-solid fa-phone"></i> @break
                                            @case('address') <i class="fa-solid fa-location-dot"></i> @break
                                            @default <i class="fa-solid fa-circle-info"></i>
                                        @endswitch
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
                            @empty
                                <span>-</span>
                            @endforelse
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
                            <p>{{ $footerSetting?->copyright_text ?? '' }}</p>
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

    {{-- JS (SEMUA FIX + DEFER) --}}
    <script defer src="{{ asset('assets/js/plugins/jquery.js') }}"></script>
    <script defer src="{{ asset('assets/js/plugins/odometer.js') }}"></script>
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
