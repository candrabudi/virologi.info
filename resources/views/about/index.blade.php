@extends('template.app')

@section('title', $about->seo_title)

@section('meta')
    <meta name="description" content="{{ $about->seo_description }}">
    <meta name="keywords" content="{{ $about->seo_keywords }}">
    <link rel="canonical" href="{{ $about->canonical_url }}">

    <meta property="og:title" content="{{ $about->og_title }}">
    <meta property="og:description" content="{{ $about->og_description }}">
    <meta property="og:image" content="{{ asset($about->og_image) }}">
@endsection

@section('content')
    <div class="rts-breadcrumb-area" style="background-image:url('{{ asset($about->breadcrumb_bg) }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-area-left">
                        <span class="pre">{{ $about->breadcrumb_pre }}</span>
                        <span class="bg-title">{{ $website->name }}</span>
                        <h1 class="title rts-text-anime-style-1" style="perspective:400px;">
                            {{ $about->page_title }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="shape-area">
            <img src="{{ asset('assets/images/about/shape/01.png') }}" class="one">
            <img src="{{ asset('assets/images/about/shape/02.png') }}" class="two">
            <img src="{{ asset('assets/images/about/shape/03.png') }}" class="three">
        </div>
    </div>

    <section class="about-invena-large-image pt--100 pb--100">
        <div class="container">
            <div class="row g-5 align-items-start">

                <div class="col-lg-6">
                    <h3 class="mb--30" style="font-size:28px; line-height:1.4;">
                        {{ $about->headline }}
                    </h3>

                    <div style="font-size:18px; line-height:1.9;">
                        {!! $about->left_content !!}
                    </div>
                </div>

                <div class="col-lg-6">
                    <h4 class="mb--20" style="font-size:22px;">
                        {{ __('Kami Membahas') }}
                    </h4>

                    {{-- <ul class="about-list mb--40" style="font-size:18px; line-height:1.9;">
                        @foreach ($about->topics as $topic)
                            <li>{{ $topic['title'] }}</li>
                        @endforeach
                    </ul> --}}

                    <div style="font-size:18px; line-height:1.9;">
                        {!! $about->right_content !!}
                    </div>

                    {{-- <div class="about-manifesto mt--40" style="font-size:18px; line-height:2;">
                        @foreach ($about->manifesto as $item)
                            <p>{{ $item['content'] }}</p>
                        @endforeach
                    </div> --}}
                </div>

            </div>
        </div>
    </section>
@endsection
