@extends('template.app')

@section('title', $article->seo_title ?? $article->title)

@push('meta')
    <meta name="description" content="{{ $article->seo_description ?? $article->excerpt }}">
    <meta name="keywords" content="{{ $article->seo_keywords }}">

    <meta property="og:title" content="{{ $article->og_title ?? $article->title }}">
    <meta property="og:description" content="{{ $article->og_description ?? $article->seo_description ?? $article->excerpt }}">
    <meta property="og:image" content="{{ $article->og_image ?? $article->thumbnail }}">
    <meta property="og:type" content="article">
@endpush

@section('content')

    {{-- Banner --}}
    <div
        class="blog-details-banner-large-image"
        style="
            background-image:url('{{ $article->thumbnail ?? asset('assets/images/blog/default.jpg') }}');
            background-size:cover;
            background-position:center;
        "
        aria-label="{{ $article->title }}"
    ></div>

    <div class="blog-details-area-main-wrapper mt-dec-180 mb-5">
        <div class="container">
            <div class="row">

                {{-- Main Content --}}
                <div class="col-lg-8">
                    <div class="blog-details-area-inner-content">

                        {{-- Meta Info --}}
                        <div class="blog-details-top-wrapper">
                            <div class="single">
                                <i class="fa-regular fa-circle-user"></i>
                                <span>virologi.info</span>
                            </div>

                            <div class="single">
                                <i class="fa-regular fa-clock"></i>
                                <span>
                                    {{ $article->published_at?->format('d M, Y') }}
                                </span>
                            </div>

                            @if($article->categories->count())
                                <div class="single">
                                    <i class="fa-regular fa-tags"></i>
                                    <span>
                                        {{ $article->categories->pluck('name')->join(', ') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h1 class="title">{{ $article->title }}</h1>

                        {{-- Excerpt --}}
                        @if ($article->excerpt)
                            <p class="disc">{{ $article->excerpt }}</p>
                        @endif

                        {{-- Content --}}
                        <div class="article-content">
                            {!! $article->content !!}
                        </div>

                        {{-- Tags --}}
                        @if ($article->tags->count())
                            <div class="row align-items-center mt--40">
                                <div class="col-lg-12">
                                    <div class="details-tag">
                                        <h6>Tags:</h6>

                                        @foreach ($article->tags as $tag)
                                            <a
                                                href="{{ url('/tag/' . $tag->slug) }}"
                                                class="tag"
                                                rel="tag"
                                            >
                                                {{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Sidebar --}}
                @include('blog.side')

            </div>
        </div>
    </div>

@endsection
