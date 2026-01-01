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
