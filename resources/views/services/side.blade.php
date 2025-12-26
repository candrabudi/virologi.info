<div class="col-xl-4 col-md-12 col-sm-12 col-12 mt_lg--60 blog-list-style pl--30 pl_md--10 pl_sm--10">

    {{-- ===============================
        SEARCH SERVICE
    =============================== --}}
    <div class="rts-single-wized search1">
        <div class="wized-header">
            <h5 class="title">Cari Layanan</h5>
        </div>
        <div class="wized-body">
            <div class="rts-search-wrapper">
                <input id="searchInput" type="text" placeholder="Cari layanan...">
                <button id="searchBtn">
                    <i class="fal fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ===============================
        CATEGORY
    =============================== --}}
    <div class="rts-single-wized Categories">
        <div class="wized-header">
            <h5 class="title">Kategori Layanan</h5>
        </div>
        <div class="wized-body">
            <ul class="single-categories" id="categoryList"></ul>
        </div>
    </div>

    {{-- ===============================
        AI DOMAIN (STATIC / CYBER)
    =============================== --}}
    <div class="rts-single-wized Categories">
        <div class="wized-header">
            <h5 class="title">Domain</h5>
        </div>
        <div class="wized-body">
            <ul class="single-categories">
                <li>
                    <a href="/services">
                        Cyber Security
                        <i class="far fa-long-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- ===============================
        RECENT SERVICES
    =============================== --}}
    <div class="rts-single-wized Recent-post">
        <div class="wized-header">
            <h5 class="title">Layanan Terbaru</h5>
        </div>
        <div class="wized-body" id="recentServices"></div>
    </div>

</div>

