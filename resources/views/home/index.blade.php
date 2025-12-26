@extends('template.app')

@section('content')
    @include('home.rts_banner')
    @include('home.rts_article')
    @include('home.rts_threatmap')
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/home/latest-articles')
        .then(res => res.json())
        .then(res => {
            if (!res.status) return;

            const wrapper = document.getElementById('homepage-blog-wrapper');
            wrapper.innerHTML = '';

            res.data.forEach(article => {
                const title = article.title.length > 70
                    ? article.title.substring(0, 70) + '...'
                    : article.title;

                wrapper.insertAdjacentHTML('beforeend', `
                    <div class="swiper-slide h-full">
                        <div class="rts-blog-h-2-wrapper">
                            <a href="/blog/${article.slug}" class="thumbnail">
                                <img 
                                    src="${article.thumbnail ?? '/assets/images/blog/default.jpg'}"
                                    alt="${article.title}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>
                            <div class="body">
                                <span>${article.categories.length ? article.categories[0].name : 'Artikel'}</span>
                                <a href="/blog/${article.slug}">
                                    <h4 class="title">${title}</h4>
                                </a>
                                <a class="rts-read-more btn-primary" href="/blog/${article.slug}">
                                    <i class="far fa-arrow-right"></i> Baca
                                </a>
                            </div>
                        </div>
                    </div>
                `);
            });

            new Swiper('.mySwiper-blog-10', {
                slidesPerView: 3,
                spaceBetween: 30,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    0: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1200: { slidesPerView: 3 }
                }
            });
        })
        .catch(err => console.error('Failed load latest articles', err));
});
</script>
@endpush
