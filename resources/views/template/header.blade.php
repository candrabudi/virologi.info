<header class="header-one style-four top-transparent-header header--sticky">
    {{-- <div class="header-top-area-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-top-one-wrapper">
                        <div class="right">
                            <p>Are you ready to grow up your business? <a href="#">Contact Us <i
                                        class="fa-solid fa-arrow-right"></i></a></p>
                        </div>
                        <div class="left">
                            <div class="mail">
                                <a href="mailto:webmaster@example.com"><i class="fal fa-envelope"></i>
                                    support@invena.com</a>
                            </div>
                            <div class="mail">
                                <a href="mailto:webmaster@example.com"><i class="fa-solid fa-phone-flip"></i>
                                    Hotline: +210-8976569</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="header-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-main-one-wrapper">
                        <div class="thumbnail">
                            <a href="#">
                                <img src="{{ $setting ? $setting->logo_rectangle : asset('logo.png')  }}" alt="finbiz-logo" style="max-width: 180px;">
                            </a>
                        </div>
                        <div class="main-header">
                            <div class="nav-area">
                                <ul class="">
                                    <li class="main-nav project-a-after">
                                        <a href="/">Beranda</a>
                                    </li>
                                    <li class="main-nav project-a-after">
                                        <a href="/about-us">Tentang Kami</a>
                                    </li>
                                    <li class="main-nav project-a-after">
                                        <a href="#">Produk</a>
                                    </li>
                                    <li class="main-nav project-a-after">
                                        <a href="#">Layanan</a>
                                    </li>
                                    <li class="main-nav project-a-after">
                                        <a href="#">E-Book</a>
                                    </li>
                                    <li class="main-nav project-a-after">
                                        <a href="/blog">Artikel</a>
                                    </li>
                                    <li class="main-nav project-a-after">
                                        <a href="#">Agent AI</a>
                                    </li>
                                </ul>
                            </div>

{{-- 
                            <div class="loader-wrapper">
                                <div class="loader">
                                </div>
                                <div class="loader-section section-left"></div>
                                <div class="loader-section section-right"></div>
                            </div> --}}
                            <div class="button-area">
                                <a href="#"
                                    class="rts-btn btn-primary ml--20 ml_sm--5 header-one-btn quote-btn">Kontak Kami</a>
                                <button id="menu-btn" class="menu menu-btn ml--20 ml_sm--5">
                                    <img class="menu-light" src="{{ asset('assets/images/icons/01.svg') }}" alt="Menu-icon">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
