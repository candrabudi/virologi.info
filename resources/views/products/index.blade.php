@extends('template.app')

@section('content')
    {{-- ===============================
        BREADCRUMB
    =============================== --}}
    <div class="rts-breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-area-left center mt-dec-blog-bread">
                        <span class="bg-title">Products</span>
                        <h1 class="title rts-text-anime-style-1">Our Products & Services</h1>
                        <p class="disc">
                            Solusi keamanan siber berbasis AI & layanan profesional
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
                    <div class="row g-5" id="productGrid"></div>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <div class="pagination" id="pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('products.side')

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1

        document.addEventListener('DOMContentLoaded', () => {
            loadProducts()
        })

        async function loadProducts(page = 1) {
            try {
                currentPage = page

                const params = new URLSearchParams(window.location.search)
                params.set('page', page)

                const res = await fetch('/api/products?' + params.toString())
                if (!res.ok) throw new Error('Failed load products')

                const json = await res.json()

                renderProducts(json.data)
                renderPagination(json.meta)

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                })
            } catch (err) {
                console.error(err)
            }
        }

        function renderProducts(products) {
            const el = document.getElementById('productGrid')
            el.innerHTML = ''

            if (!Array.isArray(products) || products.length === 0) {
                el.innerHTML = `
                <div class="col-12 text-center text-muted py-5">
                    Belum ada produk tersedia
                </div>
            `
                return
            }

            products.forEach((product, index) => {
                el.insertAdjacentHTML('beforeend', `
                <div class="col-lg-6 col-md-6 col-sm-12"
                    data-animation="fadeInUp"
                    data-delay="0.${index + 1}"
                    style="transform: translate(0px, 0px); opacity: 1;">

                    <div class="single-blog-area-one column-reverse">

                        <p>
                            ${product.product_type ?? 'Product'} /
                            <span>${product.ai_domain?.replaceAll('_',' ') ?? 'security'}</span>
                        </p>

                        <a href="/products/${product.slug}">
                            <h4 class="title">${escapeHtml(product.name)}</h4>
                        </a>

                        ${product.summary ? `
                                <p class="disc mt-2">
                                    ${escapeHtml(product.summary)}
                                </p>
                            ` : ''}

                        <div class="bottom-details">
                            <a href="/products/${product.slug}" class="thumbnail">
                                <img
                                    src="${product.thumbnail ?? '/assets/images/placeholder/product.png'}"
                                    alt="${escapeHtml(product.name)}"
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
                    onclick="loadProducts(${i})">
                    ${String(i).padStart(2, '0')}
                </button>
            `)
            }

            if (meta.current_page < meta.last_page) {
                el.insertAdjacentHTML('beforeend', `
                <button onclick="loadProducts(${meta.current_page + 1})">
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
@endpush
