@extends('template.app')

@section('content')
    <div class="rts-breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-area-left center mt-dec-blog-bread">
                        <span class="bg-title">Latest Post</span>
                        <h1 class="title rts-text-anime-style-1">Latest Posts</h1>
                        <p class="disc">Artikel terbaru seputar cybersecurity, teknologi, dan AI</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rts-blog-list-area rts-section-gapBottom mt-dec-blog-list">
        <div class="container">
            <div class="row g-5">

                <div class="col-xl-8 col-md-12 col-sm-12 col-12">
                    <div class="row g-5" id="blogGrid"></div>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <div class="pagination" id="pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('blog.side')

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1

        document.addEventListener('DOMContentLoaded', () => {
            loadArticles()
        })

        async function loadArticles(page = 1) {
            try {
                currentPage = page

                const params = new URLSearchParams(window.location.search)
                params.set('page', page)

                const res = await fetch('/api/articles?' + params.toString())
                if (!res.ok) throw new Error('Failed load articles')

                const json = await res.json()

                renderArticles(json.data)
                renderPagination(json.meta)

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                })
            } catch (err) {
                console.error(err)
            }
        }

        function renderArticles(articles) {
            const el = document.getElementById('blogGrid')
            el.innerHTML = ''

            if (!Array.isArray(articles) || articles.length === 0) {
                el.innerHTML = `
            <div class="col-12 text-center text-muted py-5">
                Tidak ada artikel ditemukan
            </div>
        `
                return
            }

            articles.forEach((article, index) => {
                el.insertAdjacentHTML('beforeend', `
            <div class="col-lg-6 col-md-6 col-sm-12"
                data-animation="fadeInUp"
                data-delay="0.${index + 1}"
                style="transform: translate(0px, 0px); opacity: 1;">

                <div class="single-blog-area-one column-reverse">
                    <p>
                        ${article.categories?.[0] ?? 'Artikel'} /
                        <span>virologi.info</span>
                    </p>

                    <a href="/blog/${article.slug}">
                        <h4 class="title">${article.title}</h4>
                    </a>

                    <div class="bottom-details">
                        <a href="/blog/${article.slug}" class="thumbnail">
                            <img
                                src="${article.thumbnail ?? '/assets/images/blog/default.jpg'}"
                                alt="${article.title}"
                                loading="lazy"
                                style="width:100%; height:220px; object-fit:cover;"
                            >
                        </a>
                    </div>
                </div>
            </div>
        `)
            })
        }

        function renderPagination(meta) {
            const el = document.getElementById('pagination')
            el.innerHTML = ''

            if (!meta || meta.last_page <= 1) return

            for (let i = 1; i <= meta.last_page; i++) {
                el.insertAdjacentHTML('beforeend', `
            <button
                class="${i === meta.current_page ? 'active' : ''}"
                onclick="loadArticles(${i})">
                ${String(i).padStart(2, '0')}
            </button>
        `)
            }

            if (meta.current_page < meta.last_page) {
                el.insertAdjacentHTML('beforeend', `
            <button onclick="loadArticles(${meta.current_page + 1})">
                <i class="fal fa-angle-double-right"></i>
            </button>
        `)
            }
        }
    </script>
@endpush
