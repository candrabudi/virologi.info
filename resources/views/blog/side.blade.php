<div class="col-xl-4 col-md-12 col-sm-12 col-12 mt_lg--60 blog-list-style pl--30 pl_md--10 pl_sm--10">
    <div class="rts-single-wized search1">
        <div class="wized-header">
            <h5 class="title">Cari Artikel</h5>
        </div>
        <div class="wized-body">
            <div class="rts-search-wrapper">
                <input id="searchInput" type="text" placeholder="Cari artikel...">
                <button id="searchBtn"><i class="fal fa-search"></i></button>
            </div>
        </div>
    </div>

    <div class="rts-single-wized Categories">
        <div class="wized-header">
            <h5 class="title">Kategori</h5>
        </div>
        <div class="wized-body">
            <ul class="single-categories" id="categoryList"></ul>
        </div>
    </div>

    <div class="rts-single-wized Recent-post">
        <div class="wized-header">
            <h5 class="title">Artikel Terakhir</h5>
        </div>
        <div class="wized-body" id="recentPosts"></div>
    </div>

    <div class="rts-single-wized tags">
        <div class="wized-header">
            <h5 class="title">Tag Terpopuler</h5>
        </div>
        <div class="wized-body">
            <div class="tags-wrapper" id="tagList"></div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        loadCategories()
        loadRecentPosts()
        loadTags()

        document.getElementById('searchBtn').addEventListener('click', doSearch)
        document.getElementById('searchInput').addEventListener('keypress', e => {
            if (e.key === 'Enter') doSearch()
        })
    })

    function doSearch() {
        const q = document.getElementById('searchInput').value
        if (!q) return
        window.location.href = `/blog?q=${encodeURIComponent(q)}`
    }

    function loadCategories() {
        axios.get('/api/article-categories')
            .then(res => {
                const el = document.getElementById('categoryList')
                el.innerHTML = ''

                res.data.data.forEach(cat => {
                    el.innerHTML += `
                    <li>
                        <a href="/categories/${cat.slug}">
                            ${cat.name}
                            <i class="far fa-long-arrow-right"></i>
                        </a>
                    </li>
                `
                })
            })
    }

    function loadRecentPosts() {
        axios.get('/api/articles/recent')
            .then(res => {
                const el = document.getElementById('recentPosts')
                el.innerHTML = ''

                res.data.data.forEach(post => {
                    el.innerHTML += `
                    <div class="recent-post-single">
                        <div class="thumbnail">
                            <a href="/blog/${post.slug}">
                                <img src="${post.thumbnail ?? '/assets/images/blog/default.jpg'}">
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
                `
                })
            })
    }

    function loadTags() {
        axios.get('/api/article-tags')
            .then(res => {
                const el = document.getElementById('tagList')
                el.innerHTML = ''

                res.data.data.forEach(tag => {
                    el.innerHTML += `
                    <a href="/tag/${tag.slug}">${tag.name}</a>
                `
                })
            })
    }
</script>
