{{-- ===============================
   PRODUCT SECTION (IMPROVED)
================================ --}}

<style>
.product-card {
    height: 100%;
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: all .35s ease;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(15,23,42,.15);
    border-color: #fecaca;
}

.product-thumb {
    position: relative;
    height: 220px;
    background: #0f172a;
    overflow: hidden;
}

.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .6s ease;
}

.product-card:hover .product-thumb img {
    transform: scale(1.06);
}

.product-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.65), rgba(0,0,0,.05));
    opacity: 0;
    transition: .35s;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-cta {
    position: absolute;
    bottom: 18px;
    left: 18px;
    right: 18px;
    text-align: center;
    opacity: 0;
    transform: translateY(10px);
    transition: .35s;
}

.product-card:hover .product-cta {
    opacity: 1;
    transform: translateY(0);
}

.product-badge {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .04em;
    text-transform: uppercase;
    background: rgba(185,28,28,.1);
    color: #b91c1c;
    border-radius: 999px;
    padding: 4px 10px;
    display: inline-block;
}

.empty-state {
    border: 1px dashed #e5e7eb;
    border-radius: 18px;
    padding: 48px 24px;
    text-align: center;
    color: #64748b;
    background: #ffffff;
}
</style>

<div class="py-20 bg-slate-50" style="margin-top: 50px; margin-bottom: 50px;">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-semibold text-slate-900">
                Produk Cyber Security
            </h2>
            <p class="mt-2 text-slate-500">
                Solusi keamanan siber berbasis AI & layanan profesional
            </p>
        </div>

        <div class="swiper mySwiperProducts">
            <div class="swiper-wrapper" id="homepage-product-wrapper">
                {{-- injected by fetch --}}
            </div>

            <div class="swiper-pagination mt-8"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('homepage-product-wrapper');

    fetch('/fetch/homepage-products')
        .then(res => res.json())
        .then(res => {
            if (!res.status || !Array.isArray(res.data) || res.data.length === 0) {
                wrapper.innerHTML = emptyState();
                return;
            }

            wrapper.innerHTML = res.data.map(renderProduct).join('');
            initSwiper();
        })
        .catch(() => {
            wrapper.innerHTML = errorState();
        });

    function renderProduct(p) {
        const url = p.cta_url ?? '#';
        const target = p.cta_type === 'external' ? '_blank' : '_self';

        return `
            <div class="swiper-slide h-auto">
                <a href="${url}" target="${target}" class="block h-full text-decoration-none text-dark">
                    <div class="product-card">
                        <div class="product-thumb">
                            <img
                                src="${p.thumbnail ?? '/assets/images/placeholder/product.png'}"
                                alt="${escapeHtml(p.name)}"
                                loading="lazy"
                            >
                            <div class="product-overlay"></div>

                            <div class="product-cta">
                                <span class="btn btn-sm btn-danger rounded-pill px-4 fw-semibold">
                                    View Detail
                                </span>
                            </div>
                        </div>

                        <div class="p-4 d-flex flex-column gap-2">
                            <span class="product-badge">
                                ${escapeHtml(p.product_type)}
                                · ${escapeHtml(p.ai_domain.replaceAll('_', ' '))}
                            </span>

                            <h5 class="fw-semibold mb-1"
                                style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                ${escapeHtml(p.name)}
                            </h5>

                            ${p.summary ? `
                                <p class="text-muted small mb-0"
                                   style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                    ${escapeHtml(p.summary)}
                                </p>
                            ` : ''}
                        </div>
                    </div>
                </a>
            </div>
        `;
    }

    function initSwiper() {
        new Swiper('.mySwiperProducts', {
            slidesPerView: 1.1,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
                1280: { slidesPerView: 4 },
            },
        });
    }

    function emptyState() {
        return `
            <div class="swiper-slide">
                <div class="empty-state">
                    <h5 class="fw-semibold mb-2">Belum ada produk</h5>
                    <p class="mb-0">
                        Produk dan layanan kami akan segera tersedia.<br>
                        Silakan cek kembali nanti.
                    </p>
                </div>
            </div>
        `;
    }

    function errorState() {
        return `
            <div class="swiper-slide">
                <div class="empty-state text-danger">
                    Gagal memuat produk
                </div>
            </div>
        `;
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
});
</script>
