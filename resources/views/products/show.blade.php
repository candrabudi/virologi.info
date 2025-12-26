@extends('template.app')

@section('title', $product->meta_title ?? $product->name)

@push('meta')
    <meta name="description" content="{{ $product->meta_description ?? $product->summary }}">
    <meta name="keywords"
        content="{{ is_array($product->meta_keywords) ? implode(',', $product->meta_keywords) : $product->meta_keywords }}">

    <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?? $product->summary }}">
    <meta property="og:image" content="{{ $product->thumbnail ?? asset('assets/images/placeholder/product.png') }}">
    <meta property="og:type" content="product">
@endpush

@section('content')

    {{-- Banner --}}
    <div class="blog-details-banner-large-image"
        style="
            background-image:url('{{ $product->thumbnail ?? asset('assets/images/placeholder/product.png') }}');
            background-size:cover;
            background-position:center;
        "
        aria-label="{{ $product->name }}"></div>

    <div class="blog-details-area-main-wrapper mt-dec-180 mb-5">
        <div class="container">
            <div class="row">

                {{-- MAIN CONTENT --}}
                <div class="col-lg-8">
                    <div class="blog-details-area-inner-content">

                        {{-- Meta Info --}}
                        <div class="blog-details-top-wrapper">

                            <div class="single">
                                <i class="fa-regular fa-cube"></i>
                                <span>{{ ucfirst($product->product_type) }}</span>
                            </div>

                            @if ($product->ai_domain)
                                <div class="single">
                                    <i class="fa-regular fa-brain"></i>
                                    <span>{{ str_replace('_', ' ', $product->ai_domain) }}</span>
                                </div>
                            @endif

                            @if ($product->is_ai_recommended)
                                <div class="single">
                                    <i class="fa-solid fa-star"></i>
                                    <span>Recommended</span>
                                </div>
                            @endif

                        </div>

                        {{-- Title --}}
                        <h1 class="title">{{ $product->name }}</h1>

                        {{-- Subtitle --}}
                        @if ($product->subtitle)
                            <p class="disc">{{ $product->subtitle }}</p>
                        @endif

                        {{-- Summary --}}
                        @if ($product->summary)
                            <div class="product-summary mt--20">
                                <p>{{ $product->summary }}</p>
                            </div>
                        @endif

                        {{-- Content --}}
                        @if ($product->content)
                            <div class="article-content mt--30">
                                {!! $product->content !!}
                            </div>
                        @endif

                        {{-- CTA --}}
                        @if ($product->cta_url)
                            <div class="product-cta mt--40">
                                <a href="{{ $product->cta_url }}"
                                    target="{{ $product->cta_type === 'external' ? '_blank' : '_self' }}"
                                    class="rts-btn btn-primary">
                                    {{ $product->cta_label ?? 'Learn More' }}
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- SIDEBAR --}}
                @include('products.side')

            </div>
        </div>
    </div>

@endsection
