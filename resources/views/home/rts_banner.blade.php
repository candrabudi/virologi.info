<style>
    .video-bg-container {
        position: relative;
        overflow: hidden;
    }

    .video-bg-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    .video-bg-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        /* gelapkan latar */
        z-index: 1;
    }

    .video-bg-content {
        position: relative;
        z-index: 2;
    }
</style>
<div class="rts-banner-ten-area banner-bg_12 rts-section-gap video-bg-container">
    <video autoplay muted loop playsinline>
        <source src="{{ asset('video_background.mp4') }}" type="video/mp4">
    </video>
    <div class="video-bg-overlay"></div>
    <div class="container video-bg-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="banner-inner-content-12">
                    <p class="pre">
                        <img src="assets/images/banner/icon/01.svg" alt="">
                        {{ $hero ? $hero->pre_title : 'Virologi' }}
                    </p>

                    <h1 class="title rts-text-anime-style-1">
                        {{ $hero ? $hero->title : 'Membangun Dunia Lebih Aman<br> dengan Siber & AI' }}
                    </h1>

                    <p class="disc">
                       {{ $hero ? $hero->subtitle : 'Bagaimana kita membuat dunia digital ini lebih aman dengan siber teknologi tinggi dan AI yang
                        dipandu dengan para techie-expert' }}
                    </p>

                    <div class="button-wrapper">
                        <a href="{{ $hero ? $hero->primary_button_url : '#' }}" class="rts-btn btn-primary btn-white">{{ $hero ? $hero->primary_button_text : 'View Live Threat Map' }}</a>
                        <a href="{{ $hero ? $hero->secondary_button_url : '#' }}" class="rts-btn btn-primary btn-border">{{ $hero ? $hero->secondary_button_text : 'Ngobrol Dengan Ahlinya' }} </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
