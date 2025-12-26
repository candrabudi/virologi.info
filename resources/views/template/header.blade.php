<header class="header-one style-four top-transparent-header header--sticky">
    <div class="header-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-main-one-wrapper">

                        <!-- LOGO -->
                        <div class="thumbnail">
                            <a href="/" aria-label="Homepage">
                                <img
                                    src="{{ $setting?->logo_rectangle_webp ?? asset('logo-180.webp') }}"
                                    width="180"
                                    height="59"
                                    alt="Finbiz Logo"
                                    fetchpriority="high"
                                    decoding="async"
                                    style="max-width:180px; height:auto;"
                                >
                            </a>
                        </div>

                        <div class="main-header">
                            <!-- NAV -->
                            <nav class="nav-area" aria-label="Main Navigation">
                                <ul>
                                    <li class="main-nav"><a href="/">Beranda</a></li>
                                    <li class="main-nav"><a href="/about-us">Tentang Kami</a></li>
                                    <li class="main-nav"><a href="#">Produk</a></li>
                                    <li class="main-nav"><a href="#">Layanan</a></li>
                                    <li class="main-nav"><a href="#">E-Book</a></li>
                                    <li class="main-nav"><a href="/blog">Artikel</a></li>
                                    <li class="main-nav"><a href="#">Agent AI</a></li>
                                </ul>
                            </nav>

                            <!-- CTA + MOBILE MENU -->
                            <div class="button-area">
                                <a href="#"
                                   class="rts-btn btn-primary ml--20 ml_sm--5 header-one-btn quote-btn">
                                    Kontak Kami
                                </a>

                                <button id="menu-btn"
                                        class="menu menu-btn ml--20 ml_sm--5"
                                        aria-label="Open Menu">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M3 6h18M3 12h18M3 18h18"
                                              stroke="currentColor"
                                              stroke-width="2"
                                              stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
