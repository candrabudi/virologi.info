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

<style>
    .video-bg-container {
        position: relative;
        overflow: hidden;
    }

    .video-bg-container video {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    .video-bg-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 1;
    }

    .video-bg-content {
        position: relative;
        z-index: 2;
    }
</style>

<div class="rts-banner-ten-area banner-bg_12 rts-section-gap video-bg-container">

    {{-- VIDEO (LAZY LOAD ONLY) --}}
    <video
        class="lazy-video"
        autoplay
        muted
        loop
        playsinline
        preload="none"
    >
        <source data-src="{{ asset('video_background.mp4') }}" type="video/mp4">
    </video>

    <div class="video-bg-overlay"></div>

    <div class="container video-bg-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="banner-inner-content-12">
                    <p class="pre">
                        <img
                            src="{{ $setting?->logo_rectangle ?? asset('logo-180.webp') }}"
                            alt=""
                            loading="lazy"
                            decoding="async"
                            width="100"
                        >
                    </p>

                    <h1 class="title rts-text-anime-style-1">
                        {!! $hero?->title ?? 'Membangun Dunia Lebih Aman<br>dengan Siber & AI' !!}
                    </h1>

                    <p class="disc">
                        {{ $hero?->subtitle ?? 'Bagaimana kita membuat dunia digital ini lebih aman dengan siber teknologi tinggi dan AI.' }}
                    </p>

                    <div class="button-wrapper">
                        <a href="{{ $hero?->primary_button_url ?? '#' }}" class="rts-btn btn-primary btn-white">
                            {{ $hero?->primary_button_text ?? 'View Live Threat Map' }}
                        </a>
                        <a href="{{ $hero?->secondary_button_url ?? '#' }}" class="rts-btn btn-primary btn-border">
                            {{ $hero?->secondary_button_text ?? 'Ngobrol Dengan Ahlinya' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- LAZY VIDEO SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const videos = document.querySelectorAll('video.lazy-video');

    if (!('IntersectionObserver' in window)) {
        videos.forEach(loadVideo);
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;

            loadVideo(entry.target);
            obs.unobserve(entry.target);
        });
    }, {
        rootMargin: '200px'
    });

    videos.forEach(video => observer.observe(video));

    function loadVideo(video) {
        const source = video.querySelector('source[data-src]');
        if (!source) return;

        source.src = source.dataset.src;
        video.load();
    }
});
</script>
