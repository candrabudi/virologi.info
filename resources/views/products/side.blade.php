<div class="col-xl-4 col-md-12 col-sm-12 col-12 mt_lg--60 blog-list-style pl--30 pl_md--10 pl_sm--10">

    <!-- Search Product -->
    <div class="rts-single-wized search1">
        <div class="wized-header">
            <h5 class="title">Cari Produk</h5>
        </div>
        <div class="wized-body">
            <div class="rts-search-wrapper">
                <input id="searchInput" type="text" placeholder="Cari produk...">
                <button id="searchBtn">
                    <i class="fal fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Product Type -->
    <div class="rts-single-wized Categories">
        <div class="wized-header">
            <h5 class="title">Tipe Produk</h5>
        </div>
        <div class="wized-body">
            <ul class="single-categories" id="productTypeList"></ul>
        </div>
    </div>

    <!-- AI Domain -->
    <div class="rts-single-wized Categories">
        <div class="wized-header">
            <h5 class="title">AI Domain</h5>
        </div>
        <div class="wized-body">
            <ul class="single-categories" id="aiDomainList"></ul>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="rts-single-wized Recent-post">
        <div class="wized-header">
            <h5 class="title">Produk Terbaru</h5>
        </div>
        <div class="wized-body" id="recentProducts"></div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    loadProductTypes()
    loadAiDomains()
    loadRecentProducts()

    document.getElementById('searchBtn').addEventListener('click', doSearch)
    document.getElementById('searchInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') doSearch()
    })
})

function doSearch() {
    const q = document.getElementById('searchInput').value.trim()
    if (!q) return
    window.location.href = `/products?q=${encodeURIComponent(q)}`
}

async function loadProductTypes() {
    try {
        const res = await fetch('/api/products/types')
        if (!res.ok) throw new Error('Failed fetch product types')

        const { data } = await res.json()
        const el = document.getElementById('productTypeList')
        el.innerHTML = ''

        if (!Array.isArray(data) || data.length === 0) {
            el.innerHTML = `<li class="text-muted">Belum ada tipe</li>`
            return
        }

        data.forEach(type => {
            el.insertAdjacentHTML('beforeend', `
                <li>
                    <a href="/products?type=${type}">
                        ${type}
                        <i class="far fa-long-arrow-right"></i>
                    </a>
                </li>
            `)
        })
    } catch (err) {
        console.error(err)
    }
}

async function loadAiDomains() {
    try {
        const res = await fetch('/api/products/domains')
        if (!res.ok) throw new Error('Failed fetch AI domains')

        const { data } = await res.json()
        const el = document.getElementById('aiDomainList')
        el.innerHTML = ''

        if (!Array.isArray(data) || data.length === 0) {
            el.innerHTML = `<li class="text-muted">Belum ada domain</li>`
            return
        }

        data.forEach(domain => {
            el.insertAdjacentHTML('beforeend', `
                <li>
                    <a href="/products?domain=${domain}">
                        ${domain.replaceAll('_', ' ')}
                        <i class="far fa-long-arrow-right"></i>
                    </a>
                </li>
            `)
        })
    } catch (err) {
        console.error(err)
    }
}

async function loadRecentProducts() {
    try {
        const res = await fetch('/api/products/recent')
        if (!res.ok) throw new Error('Failed fetch recent products')

        const { data } = await res.json()
        const el = document.getElementById('recentProducts')
        el.innerHTML = ''

        if (!Array.isArray(data) || data.length === 0) {
            el.innerHTML = `<div class="text-muted">Belum ada produk</div>`
            return
        }

        data.forEach(product => {
            el.insertAdjacentHTML('beforeend', `
                <div class="recent-post-single">
                    <div class="thumbnail">
                        <a href="/products/${product.slug}">
                            <img 
                                src="${product.thumbnail ?? '/assets/images/placeholder/product.png'}"
                                loading="lazy"
                                alt="${product.name}"
                            >
                        </a>
                    </div>
                    <div class="content-area">
                        <div class="user">
                            <i class="fal fa-cube"></i>
                            <span>${product.product_type}</span>
                        </div>
                        <a class="post-title" href="/products/${product.slug}">
                            <h6 class="title">${product.name}</h6>
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

