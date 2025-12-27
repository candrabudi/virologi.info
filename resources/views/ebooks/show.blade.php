@extends('template.app')

@section('title', $ebook->meta_title ?? $ebook->title)

@push('meta')
    <meta name="description" content="{{ $ebook->meta_description ?? $ebook->summary }}">
    <meta name="keywords"
        content="{{ is_array($ebook->meta_keywords) ? implode(',', $ebook->meta_keywords) : $ebook->meta_keywords }}">

    <meta property="og:title" content="{{ $ebook->meta_title ?? $ebook->title }}">
    <meta property="og:description" content="{{ $ebook->meta_description ?? $ebook->summary }}">
    <meta property="og:image" content="{{ $ebook->cover_image ?? asset('assets/images/placeholder/ebook.png') }}">
    <meta property="og:type" content="book">
@endpush

@section('content')

    {{-- Banner --}}
    <div class="blog-details-banner-large-image"
        style="
            background-image:url('{{ $ebook->cover_image ?? asset('assets/images/placeholder/ebook.png') }}');
            background-size:cover;
            background-position:center;
        "
        aria-label="{{ $ebook->title }}"></div>

    <div class="blog-details-area-main-wrapper mt-dec-180 mb-5">
        <div class="container">
            <div class="row">

                {{-- MAIN --}}
                <div class="col-lg-8">
                    <div class="blog-details-area-inner-content">

                        {{-- META --}}
                        <div class="blog-details-top-wrapper">

                            <div class="single">
                                <i class="fa-regular fa-book"></i>
                                <span>{{ ucfirst($ebook->level) }}</span>
                            </div>

                            <div class="single">
                                <i class="fa-regular fa-layer-group"></i>
                                <span>{{ str_replace('_', ' ', $ebook->topic) }}</span>
                            </div>

                            @if ($ebook->published_at)
                                <div class="single">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>{{ $ebook->published_at->format('d M, Y') }}</span>
                                </div>
                            @endif

                        </div>

                        {{-- TITLE --}}
                        <h1 class="title">{{ $ebook->title }}</h1>

                        {{-- SUBTITLE --}}
                        @if ($ebook->subtitle)
                            <p class="disc">{{ $ebook->subtitle }}</p>
                        @endif

                        {{-- SUMMARY --}}
                        @if ($ebook->summary)
                            <div class="article-content mt--20">
                                <p>{{ $ebook->summary }}</p>
                            </div>
                        @endif

                        {{-- LEARNING OBJECTIVES --}}
                        @if (is_array($ebook->learning_objectives))
                            <div class="mt--40">
                                <h4>Apa yang akan dipelajari</h4>
                                <ul>
                                    @foreach ($ebook->learning_objectives as $obj)
                                        <li>{{ $obj }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- CHAPTERS --}}
                        @if (is_array($ebook->chapters))
                            <div class="mt--40">
                                <h4>Daftar Bab</h4>
                                <ol>
                                    @foreach ($ebook->chapters as $chapter)
                                        <li>{{ $chapter }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif

                        {{-- CONTENT --}}
                        @if ($ebook->content)
                            <div class="article-content mt--40">
                                {!! $ebook->content !!}
                            </div>
                        @endif

                        {{-- DOWNLOAD --}}
                        @if ($ebook->file_path)
                            <div class="mt--50">
                                <a href="/ebooks/{{ $ebook->slug }}/read" class="rts-btn btn-primary" target="_blank">
                                    Download E-Book ({{ strtoupper($ebook->file_type) }})
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- SIDEBAR --}}
                @include('ebooks.side')

            </div>
        </div>
    </div>

@endsection
