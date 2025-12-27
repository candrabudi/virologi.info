<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>VIROLOGI - Professional Learning Viewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            -webkit-tap-highlight-color: transparent;
        }

        /* Pro Layout - Push Content */
        .layout-container {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        #sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            height: 100%;
            background: #fafafa;
            border-right: 1px solid #eeeeee;
            transition: margin-left 0.3s ease-in-out;
            z-index: 50;
        }

        /* Sidebar Push Logic */
        .sidebar-closed #sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        /* Mobile Adjustments */
        @media (max-width: 1024px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                margin-left: 0;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar-open #sidebar {
                transform: translateX(0);
            }
        }

        /* Professional Document Container */
        .pdf-page-container {
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
        }

        #pdf-render-container canvas {
            max-width: 100%;
            height: auto !important;
            display: block;
        }

        /* Clean Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 0;
        }

        /* Active State - No Animation */
        .active-thumb {
            background-color: #f1f5f9;
            border-left: 3px solid #0f172a !important;
        }

        /* Modern Spinner */
        .loader {
            border: 2px solid #f3f4f6;
            border-top: 2px solid #0f172a;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* No Hover Animations - Pure Professional */
        button,
        div,
        i {
            transition: none !important;
            transform: none !important;
            animation: none !important;
        }

        .loader {
            animation: spin 1s linear infinite !important;
        }
    </style>
</head>

<body class="h-screen overflow-hidden text-slate-800 sidebar-open">

    <div class="flex h-full w-full layout-container">

        <!-- SIDEBAR -->
        <aside id="sidebar">
            <div class="h-full flex flex-col">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-2">
                        <i data-lucide="book-open-check" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-[11px] font-bold text-slate-900 uppercase tracking-[0.2em]">Daftar Isi</span>
                    </div>
                    <button id="closeSidebarMobile" class="lg:hidden p-1 text-slate-400">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div id="thumbnail-container" class="flex-1 overflow-y-auto">
                    <!-- Thumbnails generated here -->
                </div>
            </div>
        </aside>

        <!-- CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER -->
            <header
                class="h-14 bg-white border-b border-slate-100 flex items-center justify-between px-4 lg:px-6 z-40 shrink-0">
                <div class="flex items-center gap-2 lg:gap-4">
                    <button id="toggleSidebar" class="p-2 text-slate-600">
                        <i data-lucide="align-left" class="w-5 h-5"></i>
                    </button>
                    <div class="h-4 w-px bg-slate-200"></div>

                    <!-- Tombol Back to Home -->
                    <button onclick="window.location.href='/'"
                        class="flex items-center gap-2 px-2 py-1.5 text-slate-500 hover:text-slate-900">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        <span class="hidden sm:inline text-[11px] font-bold uppercase tracking-wider">Home</span>
                    </button>

                    <div class="hidden sm:block h-4 w-px bg-slate-200"></div>

                    <div class="flex items-center gap-2">
                        <i data-lucide="microscope" class="w-4 h-4 text-slate-900"></i>
                        <h1 class="font-bold text-sm tracking-widest text-slate-900 uppercase">Virologi</h1>
                    </div>
                </div>

                <!-- Tools (Desktop) -->
                <div class="hidden md:flex items-center gap-8">
                    <div class="flex items-center gap-4">
                        <button id="zoom-out" class="p-1 text-slate-400 hover:text-slate-900">
                            <i data-lucide="minus-circle" class="w-4 h-4"></i>
                        </button>
                        <div class="flex items-center gap-1">
                            <i data-lucide="search" class="w-3 h-3 text-slate-300"></i>
                            <span id="zoom-percent"
                                class="text-[10px] font-bold w-10 text-center text-slate-500">200%</span>
                        </div>
                        <button id="zoom-in" class="p-1 text-slate-400 hover:text-slate-900">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="h-4 w-px bg-slate-200"></div>

                    <div class="flex items-center gap-4">
                        <button id="prev-page" class="p-1 text-slate-400 hover:text-slate-900">
                            <i data-lucide="arrow-left-to-line" class="w-4 h-4"></i>
                        </button>
                        <div class="text-[11px] font-bold flex items-center gap-1 bg-slate-50 px-3 py-1 rounded">
                            <input type="text" id="current-page" value="1"
                                class="w-6 text-center bg-transparent border-none outline-none text-slate-900">
                            <span class="text-slate-300 font-normal">/</span>
                            <span id="page-count" class="text-slate-400">0</span>
                        </div>
                        <button id="next-page" class="p-1 text-slate-400 hover:text-slate-900">
                            <i data-lucide="arrow-right-to-line" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button id="download-btn"
                        class="bg-slate-900 text-white px-5 py-2 rounded-none text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 active:opacity-80">
                        <i data-lucide="download-cloud" class="w-3.5 h-3.5"></i>
                        Download
                    </button>
                </div>
            </header>

            <!-- VIEWER -->
            <main class="flex-1 overflow-y-auto bg-[#f3f3f3] flex flex-col items-center py-8 px-4"
                id="viewer-container">
                <div id="pdf-render-container" class="pdf-page-container relative shadow-sm">
                    <div id="render-loader"
                        class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 z-20 hidden">
                        <div class="loader"></div>
                    </div>
                </div>

                <footer class="mt-12 mb-12 text-center">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <i data-lucide="shield-check" class="w-3 h-3 text-slate-300"></i>
                        <p class="text-slate-400 text-[9px] font-semibold tracking-[0.3em] uppercase">E-Learning
                            Virologi Official</p>
                    </div>
                </footer>
            </main>

            <!-- MOBILE NAV -->
            <nav class="md:hidden bg-white border-t border-slate-100 p-4 flex items-center justify-between px-8">
                <button id="mobile-prev" class="text-slate-900">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <div class="flex flex-col items-center">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter">Halaman</span>
                    <span class="text-[12px] font-black tracking-widest">
                        <span id="mobile-current">1</span> <span class="text-slate-300 font-light">/</span> <span
                            id="mobile-total">0</span>
                    </span>
                </div>
                <button id="mobile-next" class="text-slate-900">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>
            </nav>
        </div>

        <!-- OVERLAY -->
        <div id="overlay" class="fixed inset-0 bg-black/10 z-40 hidden transition-opacity duration-200"></div>
    </div>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 2.0;
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        document.getElementById('pdf-render-container').appendChild(canvas);

        const pdfUrl = encodeURIComponent(
            '{{ $ebook->file_path }}');
        const url = `/pdf-proxy?url=${pdfUrl}`;

        function renderPage(num) {
            pageRendering = true;
            document.getElementById('render-loader').classList.remove('hidden');
            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({
                    scale
                });
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                page.render({
                    canvasContext: ctx,
                    viewport
                }).promise.then(() => {
                    pageRendering = false;
                    document.getElementById('render-loader').classList.add('hidden');
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
            document.getElementById('current-page').value = num;
            document.getElementById('mobile-current').textContent = num;
            updateThumbnails(num);
        }

        const queueRender = (num) => pageRendering ? pageNumPending = num : renderPage(num);

        const nav = {
            prev: () => {
                if (pageNum > 1) {
                    pageNum--;
                    queueRender(pageNum);
                }
            },
            next: () => {
                if (pageNum < pdfDoc.numPages) {
                    pageNum++;
                    queueRender(pageNum);
                }
            },
            zoomIn: () => {
                scale += 0.2;
                updateUI();
                renderPage(pageNum);
            },
            zoomOut: () => {
                if (scale > 0.4) {
                    scale -= 0.2;
                    updateUI();
                    renderPage(pageNum);
                }
            }
        };

        const updateUI = () => document.getElementById('zoom-percent').textContent = `${Math.round(scale * 100)}%`;

        document.getElementById('prev-page').onclick = nav.prev;
        document.getElementById('next-page').onclick = nav.next;
        document.getElementById('mobile-prev').onclick = nav.prev;
        document.getElementById('mobile-next').onclick = nav.next;
        document.getElementById('zoom-in').onclick = nav.zoomIn;
        document.getElementById('zoom-out').onclick = nav.zoomOut;

        pdfjsLib.getDocument(url).promise.then(doc => {
            pdfDoc = doc;
            document.getElementById('page-count').textContent = doc.numPages;
            document.getElementById('mobile-total').textContent = doc.numPages;
            if (window.innerWidth < 640) scale = 1.2;
            updateUI();
            renderPage(pageNum);
            generateThumbnails();
        });

        function generateThumbnails() {
            const container = document.getElementById('thumbnail-container');
            container.innerHTML = '';
            for (let i = 1; i <= pdfDoc.numPages; i++) {
                const thumb = document.createElement('div');
                thumb.id = `thumb-${i}`;
                thumb.className =
                    `p-4 border-b border-slate-50 cursor-pointer flex items-center justify-between hover:bg-slate-50`;
                thumb.innerHTML = `
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-bold text-slate-300 w-4">${i}</span>
                        <span class="text-[11px] font-medium text-slate-600">Materi ${i}</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-200"></i>
                `;
                thumb.onclick = () => {
                    pageNum = i;
                    queueRender(i);
                    if (window.innerWidth < 1024) toggleSidebar();
                };
                container.appendChild(thumb);
            }
            updateThumbnails(1);
            lucide.createIcons();
        }

        function updateThumbnails(current) {
            document.querySelectorAll('[id^="thumb-"]').forEach(el => el.classList.remove('active-thumb'));
            const active = document.getElementById(`thumb-${current}`);
            if (active) {
                active.classList.add('active-thumb');
                active.scrollIntoView({
                    behavior: 'auto',
                    block: 'nearest'
                });
            }
        }

        const toggleSidebar = () => {
            const body = document.body;
            if (window.innerWidth >= 1024) {
                body.classList.toggle('sidebar-closed');
                body.classList.toggle('sidebar-open');
            } else {
                const isOpen = body.classList.contains('sidebar-open');
                body.classList.toggle('sidebar-open');
                document.getElementById('overlay').classList.toggle('hidden', isOpen);
            }
        };

        document.getElementById('toggleSidebar').onclick = toggleSidebar;
        document.getElementById('closeSidebarMobile').onclick = toggleSidebar;
        document.getElementById('overlay').onclick = toggleSidebar;

        // Logika download otomatis
        document.getElementById('download-btn').onclick = function() {
            const link = document.createElement('a');
            link.href = url;
            link.download = 'Materi_Virologi_Lengkap.pdf';
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        lucide.createIcons();
    </script>
</body>

</html>
