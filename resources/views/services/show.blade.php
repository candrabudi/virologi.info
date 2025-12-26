@extends('template.app')

@section('title', $service->seo_title ?? $service->name)

@push('meta')
    <meta name="description" content="{{ $service->seo_description ?? $service->summary }}">
    <meta name="keywords"
        content="{{ is_array($service->seo_keywords) ? implode(',', $service->seo_keywords) : $service->seo_keywords }}">

    <meta property="og:title" content="{{ $service->seo_title ?? $service->name }}">
    <meta property="og:description" content="{{ $service->seo_description ?? $service->summary }}">
    <meta property="og:image" content="{{ asset('assets/images/placeholder/service.png') }}">
    <meta property="og:type" content="service">
@endpush

@section('content')

    {{-- Banner --}}
    <div
        class="blog-details-banner-large-image"
        style="
            background-image:url('{{ asset('assets/images/placeholder/service.png') }}');
            background-size:cover;
            background-position:center;
        "
        aria-label="{{ $service->name }}"
    ></div>

    <div class="blog-details-area-main-wrapper mt-dec-180 mb-5">
        <div class="container">
            <div class="row">

                {{-- MAIN --}}
                <div class="col-lg-8">
                    <div class="blog-details-area-inner-content">

                        {{-- META --}}
                        <div class="blog-details-top-wrapper">

                            <div class="single">
                                <i class="fa-regular fa-shield-check"></i>
                                <span>{{ str_replace('_', ' ', $service->category) }}</span>
                            </div>

                            <div class="single">
                                <i class="fa-regular fa-brain"></i>
                                <span>{{ $service->ai_domain }}</span>
                            </div>

                        </div>

                        {{-- TITLE --}}
                        <h1 class="title">{{ $service->name }}</h1>

                        {{-- SHORT NAME --}}
                        @if ($service->short_name)
                            <p class="disc">{{ $service->short_name }}</p>
                        @endif

                        {{-- SUMMARY --}}
                        @if ($service->summary)
                            <div class="article-content mt--20">
                                <p>{{ $service->summary }}</p>
                            </div>
                        @endif

                        {{-- SERVICE SCOPE --}}
                        @if (is_array($service->service_scope))
                            <div class="mt--40">
                                <h4>Ruang Lingkup Layanan</h4>
                                <ul>
                                    @foreach ($service->service_scope as $scope)
                                        <li>{{ $scope }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- DELIVERABLES --}}
                        @if (is_array($service->deliverables))
                            <div class="mt--40">
                                <h4>Deliverables</h4>
                                <ul>
                                    @foreach ($service->deliverables as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- TARGET AUDIENCE --}}
                        @if (is_array($service->target_audience))
                            <div class="mt--40">
                                <h4>Target Pengguna</h4>
                                <ul>
                                    @foreach ($service->target_audience as $audience)
                                        <li>{{ $audience }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- DESCRIPTION --}}
                        @if ($service->description)
                            <div class="article-content mt--40">
                                {!! $service->description !!}
                            </div>
                        @endif

                        {{-- CTA --}}
                        <div class="mt--50">
                            <a
                                href="{{ $service->cta_url ?? '/contact' }}"
                                class="rts-btn btn-primary"
                            >
                                {{ $service->cta_label ?? 'Hubungi Kami' }}
                            </a>
                        </div>

                    </div>
                </div>

                {{-- SIDEBAR --}}
                @include('services.side')

            </div>
        </div>
    </div>

@endsection
