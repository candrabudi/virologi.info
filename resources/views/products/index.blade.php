@extends('template.app')

@section('content')
    <style>
        .video-bg-wrapper {
            position: relative;
            overflow: hidden;
            min-height: 420px;
            display: flex;
            align-items: center;
        }

        .video-bg-wrapper .bg-video {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .video-bg-wrapper .video-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 2;
        }

        .video-bg-wrapper .container {
            z-index: 3;
        }
    </style>
    <div class="partner-breadcrumb video-bg-wrapper">
        <video class="bg-video" autoplay muted loop playsinline>
            <source src="{{ asset('3129902-uhd_3840_2160_25fps.mp4') }}" type="video/mp4">
        </video>
        <div class="video-overlay"></div>
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-area-left center">
                        <span class="bg-title">Virologi</span>
                        <h1 class="title text-white">
                            Produk Digital
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="shop-area-start rts-section-gapTop ptb--100">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6 col-sm-12" data-animation="fadeInUp" data-delay="0.2"
                    style="transform: translate(0px, 0px); opacity: 1;">
                    <div class="rts-single-shop-area">
                        <a href="shop-single.html" class="thumbnail">
                            <img src="assets/images/shop/01.webp" alt="shop">
                        </a>
                        <div class="inner-content">
                            <a href="shop-single.html">
                                <h4 class="title">Music Headphones</h4>
                            </a>
                            <div class="pricing-area-wrapper">
                                <button class="rts-btn btn-primary"><i class="fa-regular fa-cart-shopping"></i> Hubungi Kami</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
