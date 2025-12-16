<style>
    .rts-blog-h-2-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
    }

    .rts-blog-h-2-wrapper .thumbnail {
        display: block;
        width: 100%;
        height: 220px;
        overflow: hidden;
    }

    .rts-blog-h-2-wrapper .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .rts-blog-h-2-wrapper .body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
    }

    .rts-blog-h-2-wrapper .body span {
        font-size: 12px;
        color: #b91c1c;
        font-weight: 600;
        margin-bottom: 8px;
    }

    /* BATAS JUDUL */
    .rts-blog-h-2-wrapper .body .title {
        font-size: 16px;
        line-height: 1.4;
        font-weight: 600;
        margin-bottom: 14px;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* maksimal 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Tombol selalu di bawah */
    .rts-blog-h-2-wrapper .rts-read-more {
        margin-top: auto;
        align-self: flex-start;
    }
</style>
<div class="our-service-area-start rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-style-five center">
                    <h2 class="title">{{ $blog ? $blog->title : 'Blog & Artikel' }}</h2>
                    <span class="pre">
                        {{ $blog ? $blog->subtitle : 'Baca artikel untuk mendalami ilmu anda dalam bidang siber dan teknologi' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-5 mt--20">
            <div class="col-lg-12">
                <div class="blog-swiper-main-wrapper-10">
                    <div class="swiper mySwiper-blog-10 pb--70">
                        <div class="swiper-wrapper" id="homepage-blog-wrapper"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    axios.get('/home/latest-articles')
        .then(res => {
            if (!res.data.status) return

            const wrapper = document.getElementById('homepage-blog-wrapper')
            wrapper.innerHTML = ''

            res.data.data.forEach(article => {
                const title =
                    article.title.length > 70 ?
                    article.title.substring(0, 70) + '...' :
                    article.title

                wrapper.innerHTML += `
            <div class="swiper-slide h-full">
                <div class="rts-blog-h-2-wrapper">
                    <a href="/blog/${article.slug}" class="thumbnail">
                        <img src="${article.thumbnail ?? '/assets/images/blog/default.jpg'}" alt="${article.title}">
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
            `
            })

            new Swiper('.mySwiper-blog-10', {
                slidesPerView: 3,
                spaceBetween: 30,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1
                    },
                    768: {
                        slidesPerView: 2
                    },
                    1200: {
                        slidesPerView: 3
                    }
                }
            })
        })
</script>
