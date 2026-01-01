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
        aria-label="{{ $ebook->title }}">
    </div>

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

                        {{-- SUMMARY --}}
                        @if ($ebook->summary)
                            <div class="article-content mt--20">
                                <p>{{ $ebook->summary }}</p>
                            </div>
                        @endif

                        {{-- CONTENT --}}
                        @if ($ebook->content)
                            <div class="article-content mt--40">
                                {!! $ebook->content !!}
                            </div>
                        @endif

                        {{-- ACTION BUTTONS --}}
                        @if ($ebook->file_path)
                            <div class="ebook-action-inline mt-4">
                                {{-- BACA --}}
                                <a href="/ebooks/{{ $ebook->slug }}/read" target="_blank"
                                    class="rts-btn btn-secondary ebook-btn">
                                    <i class="fa-regular fa-book-open"></i>
                                    <span>Baca E-Book</span>
                                </a>

                                {{-- DOWNLOAD --}}
                                <button type="button" class="rts-btn btn-primary ebook-btn"
                                    onclick="downloadEbook(
                                            '{{ $ebook->file_path }}',
                                            '{{ Str::slug($ebook->title) }}.{{ $ebook->file_type }}'
                                        )">
                                    <i class="fa-regular fa-download"></i>
                                    <span>Download</span>
                                </button>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- SIDEBAR --}}
                @include('ebooks.side')

            </div>
        </div>
    </div>

    {{-- CSS OVERRIDE --}}
    <style>
        .ebook-action-inline {
            display: flex;
            gap: 14px;
            max-width: 520px;
            /* biar agak panjang */
        }

        .ebook-action-inline .ebook-btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100% !important;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1;
            border-radius: 6px;
        }

        .ebook-action-inline .ebook-btn i {
            font-size: 15px;
        }
    </style>

    {{-- DOWNLOAD SCRIPT --}}
    <script>
        function downloadEbook(url, filename) {
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal download file');
                    }
                    return response.blob();
                })
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(link.href);
                })
                .catch(error => {
                    alert(error.message);
                });
        }
    </script>

@endsection
