@extends('template.app')

@section('title', 'E-Books & Learning Resources')

@section('content')
    {{-- ===============================
        BREADCRUMB
    =============================== --}}
    <div class="rts-breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-area-left center mt-dec-blog-bread">
                        <span class="bg-title">E-Books</span>
                        <h1 class="title rts-text-anime-style-1">
                            Cyber Security E-Books
                        </h1>
                        <p class="disc">
                            Materi pembelajaran dan panduan keamanan siber
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===============================
        EBOOK LIST
    =============================== --}}
    <div class="rts-blog-list-area rts-section-gapBottom mt-dec-blog-list">
        <div class="container">
            <div class="row g-5">

                {{-- GRID --}}
                <div class="col-xl-8 col-md-12 col-sm-12 col-12">
                    <div class="row g-5" id="ebookGrid"></div>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <div class="pagination" id="pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                @include('ebooks.side')

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1

        document.addEventListener('DOMContentLoaded', () => {
            loadEbooks()
        })

        async function loadEbooks(page = 1) {
            try {
                currentPage = page

                const params = new URLSearchParams(window.location.search)
                params.set('page', page)

                const res = await fetch('/api/ebooks?' + params.toString())
                if (!res.ok) throw new Error('Failed load ebooks')

                const json = await res.json()

                renderEbooks(json.data)
                renderPagination(json.meta)

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                })
            } catch (err) {
                console.error(err)
            }
        }

        function renderEbooks(ebooks) {
            const el = document.getElementById('ebookGrid')
            el.innerHTML = ''

            if (!Array.isArray(ebooks) || ebooks.length === 0) {
                el.innerHTML = `
            <div class="col-12 text-center text-muted py-5">
                Belum ada e-book tersedia
            </div>
        `
                return
            }

            ebooks.forEach((ebook, index) => {
                el.insertAdjacentHTML('beforeend', `
            <div class="col-lg-6 col-md-6 col-sm-12"
                data-animation="fadeInUp"
                data-delay="0.${index + 1}">

                <div class="border rounded-3 p-4 h-100 bg-white ebook-box">

                    <div class="row g-3 align-items-center">

                        <div class="col-4">
                            <img
                                src="${ebook.cover_image ?? '/assets/images/placeholder/ebook.png'}"
                                alt="${escapeHtml(ebook.title)}"
                                loading="lazy"
                                style="width:100%; max-height:160px; object-fit:contain;"
                            >
                        </div>

                        <div class="col-8">
                            <div class="mb-2">
                                <span class="badge bg-light text-dark border">
                                    ${ebook.topic.replaceAll('_', ' ')}
                                </span>
                                <span class="badge bg-secondary ms-1">
                                    ${ebook.level}
                                </span>
                            </div>

                            <h5 class="fw-semibold mb-2">
                                <a href="/ebooks/${ebook.slug}" class="text-dark text-decoration-none">
                                    ${escapeHtml(ebook.title)}
                                </a>
                            </h5>

                            ${ebook.subtitle ? `
                                    <p class="text-muted small mb-3">
                                        ${escapeHtml(ebook.subtitle)}
                                    </p>
                                ` : ''}

                            <a href="/ebooks/${ebook.slug}" class="btn btn-outline-dark btn-sm">
                                Lihat E-Book
                            </a>
                        </div>

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
                    onclick="loadEbooks(${i})">
                    ${String(i).padStart(2, '0')}
                </button>
            `)
            }

            if (meta.current_page < meta.last_page) {
                el.insertAdjacentHTML('beforeend', `
                <button onclick="loadEbooks(${meta.current_page + 1})">
                    <i class="fal fa-angle-double-right"></i>
                </button>
            `)
            }
        }

        function escapeHtml(str) {
            return String(str ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;')
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadLevels()
            loadTopics()
            loadRecentEbooks()

            document.getElementById('searchBtn').addEventListener('click', doSearch)
            document.getElementById('searchInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') doSearch()
            })
        })

        function doSearch() {
            const q = document.getElementById('searchInput').value.trim()
            if (!q) return
            window.location.href = `/ebooks?q=${encodeURIComponent(q)}`
        }

        async function loadLevels() {
            try {
                const res = await fetch('/api/ebooks/levels')
                if (!res.ok) throw new Error('Failed fetch levels')

                const {
                    data
                } = await res.json()
                const el = document.getElementById('levelList')
                el.innerHTML = ''

                if (!Array.isArray(data) || data.length === 0) {
                    el.innerHTML = `<li class="text-muted">Belum ada level</li>`
                    return
                }

                data.forEach(level => {
                    el.insertAdjacentHTML('beforeend', `
                <li>
                    <a href="/ebooks?level=${level}">
                        ${level}
                        <i class="far fa-long-arrow-right"></i>
                    </a>
                </li>
            `)
                })
            } catch (err) {
                console.error(err)
            }
        }

        async function loadTopics() {
            try {
                const res = await fetch('/api/ebooks/topics')
                if (!res.ok) throw new Error('Failed fetch topics')

                const {
                    data
                } = await res.json()
                const el = document.getElementById('topicList')
                el.innerHTML = ''

                if (!Array.isArray(data) || data.length === 0) {
                    el.innerHTML = `<li class="text-muted">Belum ada topik</li>`
                    return
                }

                data.forEach(topic => {
                    el.insertAdjacentHTML('beforeend', `
                <li>
                    <a href="/ebooks?topic=${topic}">
                        ${topic.replaceAll('_',' ')}
                        <i class="far fa-long-arrow-right"></i>
                    </a>
                </li>
            `)
                })
            } catch (err) {
                console.error(err)
            }
        }

        async function loadRecentEbooks() {
            try {
                const res = await fetch('/api/ebooks/recent')
                if (!res.ok) throw new Error('Failed fetch recent ebooks')

                const {
                    data
                } = await res.json()
                const el = document.getElementById('recentEbooks')
                el.innerHTML = ''

                if (!Array.isArray(data) || data.length === 0) {
                    el.innerHTML = `<div class="text-muted">Belum ada e-book</div>`
                    return
                }

                data.forEach(ebook => {
                    el.insertAdjacentHTML('beforeend', `
                <div class="recent-post-single">
                    <div class="thumbnail">
                        <a href="/ebooks/${ebook.slug}">
                            <img
                                src="${ebook.cover_image ?? '/assets/images/placeholder/ebook.png'}"
                                loading="lazy"
                                alt="${ebook.title}"
                            >
                        </a>
                    </div>
                    <div class="content-area">
                        <div class="user">
                            <i class="fal fa-book"></i>
                            <span>${ebook.level}</span>
                        </div>
                        <a class="post-title" href="/ebooks/${ebook.slug}">
                            <h6 class="title">${ebook.title}</h6>
                        </a>
                    </div>
                </div>
            `)
                })
            } catch (err) {
                console.error(err)
            }
        }
    </script>
@endpush
