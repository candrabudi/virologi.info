<div class="col-xl-4 col-md-12 col-sm-12 col-12 mt_lg--60 blog-list-style pl--30 pl_md--10 pl_sm--10">

    <!-- Search -->
    <div class="rts-single-wized search1">
        <div class="wized-header">
            <h5 class="title">Cari Artikel</h5>
        </div>
        <div class="wized-body">
            <div class="rts-search-wrapper">
                <input id="searchInput" type="text" placeholder="Cari artikel...">
                <button id="searchBtn">
                    <i class="fal fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="rts-single-wized Categories">
        <div class="wized-header">
            <h5 class="title">Kategori</h5>
        </div>
        <div class="wized-body">
            <ul class="single-categories" id="categoryList"></ul>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="rts-single-wized Recent-post">
        <div class="wized-header">
            <h5 class="title">Artikel Terakhir</h5>
        </div>
        <div class="wized-body" id="recentPosts"></div>
    </div>

    <!-- Tags -->
    <div class="rts-single-wized tags">
        <div class="wized-header">
            <h5 class="title">Tag Terpopuler</h5>
        </div>
        <div class="wized-body">
            <div class="tags-wrapper" id="tagList"></div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadCategories()
        loadRecentPosts()
        loadTags()

        document.getElementById('searchBtn').addEventListener('click', doSearch)
        document.getElementById('searchInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') doSearch()
        })
    })

    function doSearch() {
        const q = document.getElementById('searchInput').value.trim()
        if (!q) return
        window.location.href = `/blog?q=${encodeURIComponent(q)}`
    }

    async function loadCategories() {
        try {
            const res = await fetch('/api/article-categories')
            if (!res.ok) throw new Error('Failed fetch categories')

            const {
                data
            } = await res.json()
            const el = document.getElementById('categoryList')
            el.innerHTML = ''

            data.forEach(cat => {
                el.insertAdjacentHTML('beforeend', `
                <li>
                    <a href="/blog?category=${cat.slug}">
                        ${cat.name}
                        <i class="far fa-long-arrow-right"></i>
                    </a>
                </li>
            `)
            })
        } catch (err) {
            console.error(err)
        }
    }

    async function loadRecentPosts() {
        try {
            const res = await fetch('/api/articles/recent')
            if (!res.ok) throw new Error('Failed fetch recent posts')

            const {
                data
            } = await res.json()
            const el = document.getElementById('recentPosts')
            el.innerHTML = ''

            data.forEach(post => {
                el.insertAdjacentHTML('beforeend', `
                <div class="recent-post-single">
                    <div class="thumbnail">
                        <a href="/blog/${post.slug}">
                            <img 
                                src="${post.thumbnail ?? '/assets/images/blog/default.jpg'}"
                                loading="lazy"
                                alt="${post.title}"
                            >
                        </a>
                    </div>
                    <div class="content-area">
                        <div class="user">
                            <i class="fal fa-clock"></i>
                            <span>${post.published_at}</span>
                        </div>
                        <a class="post-title" href="/blog/${post.slug}">
                            <h6 class="title">${post.title}</h6>
                        </a>
                    </div>
                </div>
            `)
            })
        } catch (err) {
            console.error(err)
        }
    }

    async function loadTags() {
        try {
            const res = await fetch('/api/article-tags')
            if (!res.ok) throw new Error('Failed fetch tags')

            const {
                data
            } = await res.json()
            const el = document.getElementById('tagList')
            el.innerHTML = ''

            data.forEach(tag => {
                el.insertAdjacentHTML('beforeend', `
                <a href="/blog?tag=${tag.slug}">${tag.name}</a>
            `)
            })
        } catch (err) {
            console.error(err)
        }
    }
</script>
