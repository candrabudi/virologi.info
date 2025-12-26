{{-- ===============================
   EBOOK SECTION
================================ --}}

<style>
    .ebook-card {
        height: 100%;
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: all .35s ease;
    }

    .ebook-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, .15);
        border-color: #c7d2fe;
    }

    .ebook-cover {
        position: relative;
        height: 240px;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ebook-cover img {
        max-height: 90%;
        max-width: 90%;
        object-fit: contain;
        transition: transform .5s ease;
    }

    .ebook-card:hover .ebook-cover img {
        transform: scale(1.05);
    }

    .ebook-level {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 999px;
    }

    .level-beginner {
        background: #dcfce7;
        color: #166534;
    }

    .level-intermediate {
        background: #fef9c3;
        color: #854d0e;
    }

    .level-advanced {
        background: #fee2e2;
        color: #991b1b;
    }

    .ebook-topic {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #475569;
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
                E-Books & Learning Resources
            </h2>
            <p class="mt-2 text-slate-500">
                Panduan dan materi pembelajaran keamanan siber
            </p>
        </div>

        <div class="swiper mySwiperEbooks" style="margin-top: 50px;">
            <div class="swiper-wrapper" id="homepage-ebook-wrapper">
                {{-- injected by fetch --}}
            </div>

        </div>
        <div class="swiper-pagination mt-8" style="margin-top: 50px;"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('homepage-ebook-wrapper');

        fetch('/fetch/homepage-ebooks')
            .then(res => res.json())
            .then(res => {
                if (!res.status || !Array.isArray(res.data) || res.data.length === 0) {
                    wrapper.innerHTML = emptyState();
                    return;
                }

                wrapper.innerHTML = res.data.map(renderEbook).join('');
                initSwiper();
            })
            .catch(() => {
                wrapper.innerHTML = errorState();
            });

        function renderEbook(e) {
            const url = e.slug ? `/ebooks/${e.slug}` : '#';

            return `
            <div class="swiper-slide h-auto">
                <a href="${url}" class="block h-full text-decoration-none text-dark">
                    <div class="ebook-card">
                        <div class="ebook-cover">
                            <img
                                src="${e.cover_image ?? '/assets/images/placeholder/ebook.png'}"
                                alt="${escapeHtml(e.title)}"
                                loading="lazy"
                            >
                        </div>

                        <div class="p-4 d-flex flex-column gap-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="ebook-level level-${e.level}">
                                    ${escapeHtml(e.level)}
                                </span>
                                <span class="ebook-topic">
                                    ${escapeHtml(e.topic.replaceAll('_', ' '))}
                                </span>
                            </div>

                            <h5 class="fw-semibold mb-1"
                                style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                ${escapeHtml(e.title)}
                            </h5>

                            ${e.subtitle ? `
                                <p class="text-muted small mb-0"
                                   style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    ${escapeHtml(e.subtitle)}
                                </p>
                            ` : ''}

                            <div class="mt-2 text-sm text-muted">
                                ${e.page_count ? `${e.page_count} pages` : ''}
                                ${e.author ? ` · ${escapeHtml(e.author)}` : ''}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        `;
        }

        function initSwiper() {
            new Swiper('.mySwiperEbooks', {
                slidesPerView: 1.2,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    },
                    1280: {
                        slidesPerView: 4
                    },
                },
            });
        }

        function emptyState() {
            return `
            <div class="swiper-slide">
                <div class="empty-state">
                    <h5 class="fw-semibold mb-2">Belum ada e-book</h5>
                    <p class="mb-0">
                        Materi pembelajaran akan segera tersedia.<br>
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
                    Gagal memuat e-book
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
