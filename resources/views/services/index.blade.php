@extends('template.app')

@section('content')
    <div class="rts-breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-area-left center mt-dec-blog-bread">
                        <span class="bg-title">Services</span>
                        <h1 class="title rts-text-anime-style-1">
                            Cyber Security Services
                        </h1>
                        <p class="disc">
                            Layanan profesional keamanan siber untuk organisasi Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rts-blog-list-area rts-section-gapBottom mt-dec-blog-list">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-8 col-md-12 col-sm-12 col-12">
                    <div class="row g-5" id="serviceGrid"></div>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <div class="pagination" id="pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('services.side')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1

        document.addEventListener('DOMContentLoaded', () => {
            loadServices()
        })

        async function loadServices(page = 1) {
            try {
                currentPage = page

                const params = new URLSearchParams(window.location.search)
                params.set('page', page)

                const res = await fetch('/api/services?' + params.toString())
                if (!res.ok) throw new Error('Failed load services')

                const json = await res.json()

                renderServices(json.data)
                renderPagination(json.meta)

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                })
            } catch (err) {
                console.error(err)
            }
        }

        function renderServices(services) {
            const el = document.getElementById('serviceGrid')
            el.innerHTML = ''

            if (!Array.isArray(services) || services.length === 0) {
                el.innerHTML = `
            <div class="col-12 text-center text-muted py-5">
                Belum ada layanan tersedia
            </div>
        `
                return
            }

            services.forEach((service, index) => {
                const thumbnail = service.thumbnail ?? '/assets/images/placeholder/service.png'

                el.insertAdjacentHTML('beforeend', `
            <div class="col-lg-4 col-md-6 col-sm-12"
                data-animation="fadeInUp"
                data-delay="0.${index + 1}">

                <div class="card h-100 border-0 shadow-sm service-card">

                    <a href="/services/${service.slug}" class="position-relative">
                        <img
                            src="${thumbnail}"
                            class="card-img-top"
                            alt="${escapeHtml(service.name)}"
                            loading="lazy"
                            style="height:220px; object-fit:cover;"
                        >
                        <span class="badge bg-primary position-absolute top-0 start-0 m-3">
                            ${service.category.replaceAll('_', ' ')}
                        </span>
                    </a>

                    <div class="card-body d-flex flex-column">

                        <small class="text-muted mb-2">
                            cyber security
                        </small>

                        <h5 class="card-title">
                            <a href="/services/${service.slug}" class="text-dark text-decoration-none">
                                ${escapeHtml(service.name)}
                            </a>
                        </h5>

                        ${service.summary ? `
                                <p class="card-text text-muted mt-2">
                                    ${escapeHtml(service.summary)}
                                </p>
                            ` : ''}

                        <div class="mt-auto">
                            <a href="/services/${service.slug}" class="btn btn-outline-primary btn-sm w-100">
                                Lihat Layanan
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
                onclick="loadServices(${i})">
                ${String(i).padStart(2, '0')}
            </button>
        `)
            }

            if (meta.current_page < meta.last_page) {
                el.insertAdjacentHTML('beforeend', `
            <button onclick="loadServices(${meta.current_page + 1})">
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
            loadCategories()
            loadRecentServices()

            document.getElementById('searchBtn').addEventListener('click', doSearch)
            document.getElementById('searchInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') doSearch()
            })
        })

        function doSearch() {
            const q = document.getElementById('searchInput').value.trim()
            if (!q) return
            window.location.href = `/services?q=${encodeURIComponent(q)}`
        }

        async function loadCategories() {
            try {
                const res = await fetch('/api/services/categories')
                if (!res.ok) throw new Error('Failed fetch categories')

                const {
                    data
                } = await res.json()
                const el = document.getElementById('categoryList')
                el.innerHTML = ''

                if (!Array.isArray(data) || data.length === 0) {
                    el.innerHTML = `<li class="text-muted">Belum ada kategori</li>`
                    return
                }

                data.forEach(cat => {
                    el.insertAdjacentHTML('beforeend', `
                <li>
                    <a href="/services?category=${cat}">
                        ${cat.replaceAll('_',' ')}
                        <i class="far fa-long-arrow-right"></i>
                    </a>
                </li>
            `)
                })
            } catch (err) {
                console.error(err)
            }
        }

        async function loadRecentServices() {
            try {
                const res = await fetch('/api/services/recent')
                if (!res.ok) throw new Error('Failed fetch recent services')

                const {
                    data
                } = await res.json()
                const el = document.getElementById('recentServices')
                el.innerHTML = ''

                if (!Array.isArray(data) || data.length === 0) {
                    el.innerHTML = `<div class="text-muted">Belum ada layanan</div>`
                    return
                }

                data.forEach(service => {
                    el.insertAdjacentHTML('beforeend', `
                <div class="recent-post-single">
                    <div class="content-area">
                        <div class="user">
                            <i class="fal fa-shield-check"></i>
                            <span>${service.category.replaceAll('_',' ')}</span>
                        </div>
                        <a class="post-title" href="/services/${service.slug}">
                            <h6 class="title">${escapeHtml(service.name)}</h6>
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
