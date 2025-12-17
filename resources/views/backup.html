<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Live Cyber Threat Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        malware: '#ef4444',
                        phishing: '#a855f7',
                        exploit: '#22d3ee',
                        cyberblue: '#00bfff',
                        gridline: '#1e293b'
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['Consolas', 'Menlo', 'monospace']
                    }
                }
            }
        }
    </script>

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', system-ui, sans-serif;
            /* padding-top: 30px; */
            padding-bottom: 0;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03) 0%, rgba(0, 0, 0, 0.95) 40%, #000 100%),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                radial-gradient(circle at 50% 20%, rgba(0, 180, 255, 0.15), transparent 55%);
            background-size:
                100% 100%,
                80px 80px,
                80px 80px,
                100% 100%;
            background-color: #000;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(to bottom,
                    transparent 0%,
                    rgba(0, 180, 255, 0.05) 50%,
                    transparent 100%);
            background-size: 100% 6px;
            animation: scan 6s linear infinite;
            pointer-events: none;
        }

        @keyframes scan {
            from {
                background-position-y: 0;
            }

            to {
                background-position-y: 100%;
            }
        }


        #map-container {
            position: absolute;
            top: 60px;
            left: 0;
            width: 100%;
            height: calc(100% - 60px);
            z-index: 10;
            touch-action: none;
            transform-origin: center center;
            transition: transform 0.05s ease-out, left 0.3s ease, right 0.3s ease, width 0.3s ease;

            opacity: 0.55;

            filter:
                saturate(0.7) brightness(0.85) contrast(0.95) blur(0.15px);

            mix-blend-mode: screen;
        }

        #map-container::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 15;
            background:
                linear-gradient(to bottom,
                    rgba(0, 0, 0, 0.85) 0%,
                    rgba(0, 0, 0, 0.45) 8%,
                    rgba(0, 0, 0, 0.15) 16%,
                    rgba(0, 0, 0, 0) 30%,
                    rgba(0, 0, 0, 0) 70%,
                    rgba(0, 0, 0, 0.15) 84%,
                    rgba(0, 0, 0, 0.45) 92%,
                    rgba(0, 0, 0, 0.85) 100%);
        }


        #map-container::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 50% 40%,
                    rgba(0, 180, 255, 0.12),
                    transparent 60%);
            mix-blend-mode: screen;
            z-index: 11;
        }

        #map-interact-layer {
            position: absolute;
            inset: 0;
            cursor: grab;
            z-index: 20;
        }

        #map-interact-layer:active {
            cursor: grabbing;
        }

        iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            pointer-events: none;
            /* filter: grayscale(1) brightness(0.8); */
        }


        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #00bfff;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0f172a;
        }

        .left-panel-hidden {
            transform: translateX(-100%);
            opacity: 0;
        }

        .right-panel-hidden {
            transform: translateX(100%);
            opacity: 0;
        }

        #left-panel {
            left: 1rem;
        }

        #right-panel {
            right: 1rem;
        }

        #left-panel-toggle {
            left: 1rem;
        }

        #right-panel-toggle {
            right: 1rem;
        }

        @media (min-width: 1024px) {

            /* Desktop default view (Panels open) - Map diperkecil dan ditengah */
            #map-container {
                left: 360px;
                /* 340px panel + 20px margin/gap */
                right: 360px;
                /* 340px panel + 20px margin/gap */
                width: auto;
                /* Didefinisikan oleh left/right */
                height: calc(100% - 60px);
            }

            #left-panel {
                top: 5rem;
                bottom: 1.5rem;
                width: 340px;
                height: auto;
            }

            #left-panel-toggle {
                left: 340px;
                transform: translateX(0);
            }

            #right-panel-toggle {
                right: 340px;
                transform: translateX(0);
            }

            .map-expand-left {
                left: 1rem !important;
            }

            .map-expand-right {
                right: 1rem !important;
            }

            #left-panel.left-panel-hidden+#left-panel-toggle {
                left: 0.5rem;
                transform: translateX(0);
            }

            #right-panel.right-panel-hidden+#right-panel-toggle {
                right: 0.5rem;
                transform: translateX(0);
            }

            #zoom-controls {
                top: 70px;
                right: 1.5rem;
            }
        }

        @media (max-width: 1023px) {
            .legend-panel {
                display: flex !important;
                position: fixed !important;
                top: 50px !important;
                bottom: auto !important;
                left: 0 !important;
                right: 0 !important;
                transform: none !important;
                width: 100% !important;
                justify-content: space-around !important;
                padding: 0.5rem 0 !important;
                z-index: 45 !important;
                backdrop-filter: blur(8px) !important;
                border-bottom: 1px solid #00bfff20 !important;
                pointer-events: auto !important;
                height: 35px !important;
                gap: 0 !important;
            }

            #left-panel {
                left: 0 !important;
                right: 0 !important;
                top: auto !important;
                bottom: 0 !important;
                width: 100% !important;
                height: 440px !important;
                max-width: none !important;
                border-radius: 1rem 1rem 0 0 !important;
                border-left: none !important;
                border-right: none !important;
                transform: none !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            #map-container {
                top: -115px !important;
                bottom: 280px !important;
                height: calc(100% - 95px - 280px) !important;
            }

            #left-panel-toggle {
                display: none !important;
            }

            #right-panel {
                display: none !important;
            }

            #right-panel-toggle {
                display: none !important;
            }

            #zoom-controls {
                top: 105px;
                right: 1rem;
            }
        }

        .grid-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 5;
            background-image: linear-gradient(to right, theme('colors.gridline') 1px, transparent 1px),
                linear-gradient(to bottom, theme('colors.gridline') 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.15;
            pointer-events: none;
        }
    </style>
</head>

<body class="relative">

    <div class="grid-background"></div>
    <div id="map-container" style="transform: translate(0px, 0px) scale(1);">
        <iframe id="raven-iframe" src="{{ asset('raven/src/raven.html') }}"></iframe>
        <div id="map-interact-layer" class="absolute top-0 left-0 w-full h-full"></div>
    </div>
    <div
        class="absolute top-0 left-0 right-0 z-40 pointer-events-none
           flex flex-col items-center justify-center
           h-[52px] sm:h-[60px]
           bg-slate-950/30 backdrop-blur-md
           border-b border-white/5">

        <h1
            class="text-[11px] sm:text-xl
               font-extrabold text-slate-100
               tracking-[0.25em] sm:tracking-[0.4em]
               drop-shadow-[0_0_8px_rgba(255,255,255,0.15)]
               text-center px-4 whitespace-nowrap">
            LIVE CYBER THREAT MAP
        </h1>

        <div
            class="hidden sm:block mt-1
               text-xs text-cyan-400
               tracking-widest font-medium
               drop-shadow-[0_0_10px_rgba(0,191,255,0.8)]">
            REAL-TIME GLOBAL ATTACK ACTIVITY
        </div>

        <div
            class="block sm:hidden
               text-[9px] text-cyan-400
               tracking-[0.18em]
               drop-shadow-[0_0_8px_rgba(0,191,255,0.7)]">
            REAL-TIME ATTACKS
        </div>
    </div>


    <div id="left-panel"
        class="absolute top-20 bottom-6 w-[340px] z-40 pointer-events-none transition-all duration-300">
        <div
            class="h-full rounded-2xl bg-slate-950/60 backdrop-blur-xl border border-cyberblue/30 shadow-3xl flex flex-col p-0 overflow-hidden drop-shadow-[0_0_15px_rgba(0,191,255,0.4)]">

            <div class="p-4 flex-shrink-0 border-b border-white/5">
                <div class="text-[10px] tracking-widest text-slate-300 mb-2 uppercase font-mono">RECENT DAILY ATTACKS
                </div>
                <canvas id="attackChart" height="90"></canvas>
            </div>

            <div class="px-4 pt-3 pb-3 flex-shrink-0">
                <div class="text-sm tracking-widest text-cyberblue drop-shadow-[0_0_6px_rgba(0,191,255,0.7)] font-bold">
                    ATTACK LOG STREAM
                </div>
            </div>

            <div id="attack-list"
                class="flex-1 overflow-y-auto px-4 pb-4 space-y-2 custom-scrollbar pointer-events-auto">
            </div>
        </div>
    </div>
    <button id="left-panel-toggle" onclick="toggleLeftPanel()" title="Sembunyikan Panel Kiri"
        class="absolute top-20 p-2 rounded-full bg-cyberblue/80 hover:bg-cyberblue z-50 pointer-events-auto shadow-lg transition-all duration-300 drop-shadow-[0_0_8px_rgba(0,191,255,0.8)]">
        <svg id="left-icon" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <div id="right-panel"
        class="absolute top-20 bottom-6 w-[340px] z-40 pointer-events-none transition-all duration-300">
        <div
            class="h-full rounded-2xl bg-slate-950/60 backdrop-blur-xl border border-cyberblue/30 shadow-3xl flex flex-col p-0 overflow-hidden drop-shadow-[0_0_15px_rgba(0,191,255,0.4)]">

            <div class="p-4 flex-shrink-0 border-b border-white/5">
                <div class="text-[10px] tracking-widest text-slate-300 mb-3 uppercase font-mono">GLOBAL METRICS</div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div id="total-attacks"
                            class="text-3xl font-extrabold text-cyberblue drop-shadow-[0_0_8px_rgba(0,191,255,.5)]">0
                        </div>
                        <div class="text-[9px] text-slate-400 uppercase tracking-wider mt-1">Total Attacks Today</div>
                    </div>
                    <div class="text-center">
                        <div id="top-country"
                            class="text-3xl font-extrabold text-exploit drop-shadow-[0_0_8px_rgba(34,211,238,.5)]">N/A
                        </div>
                        <div class="text-[9px] text-slate-400 uppercase tracking-wider mt-1">Most Targeted Country</div>
                    </div>
                </div>
            </div>

            <div class="px-4 pt-3 pb-3 flex-shrink-0">
                <div class="text-sm tracking-widest text-cyberblue drop-shadow-[0_0_6px_rgba(0,191,255,.7)] font-bold">
                    TOP ATTACK SOURCES
                </div>
            </div>

            <div id="top-sources-list"
                class="flex-1 overflow-y-auto px-4 pb-4 space-y-2 custom-scrollbar pointer-events-auto">
            </div>
        </div>
    </div>
    <button id="right-panel-toggle" onclick="toggleRightPanel()" title="Sembunyikan Panel Kanan"
        class="absolute top-20 p-2 rounded-full bg-cyberblue/80 hover:bg-cyberblue z-50 pointer-events-auto shadow-lg transition-all duration-300 drop-shadow-[0_0_8px_rgba(0,191,255,0.8)]">
        <svg id="right-icon" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <div id="zoom-controls" class="absolute z-50 flex flex-col space-y-2 pointer-events-auto">
        <button onclick="zoomIn()" title="Perbesar (Zoom In)"
            class="p-2 rounded-full bg-cyberblue/80 hover:bg-cyberblue text-white shadow-lg drop-shadow-[0_0_8px_rgba(0,191,255,0.8)]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
        <button onclick="zoomOut()" title="Perkecil (Zoom Out)"
            class="p-2 rounded-full bg-cyberblue/80 hover:bg-cyberblue text-white shadow-lg drop-shadow-[0_0_8px_rgba(0,191,255,0.8)]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
        </button>
    </div>

    <div
        class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-8 z-40 text-xs text-slate-300 pointer-events-none legend-panel">
        <div class="flex items-center gap-2">
            <span
                class="w-3 h-3 rounded-full bg-malware shadow-[0_0_12px_rgba(239,68,68,1)] ring-2 ring-malware/50"></span>
            <span class="font-medium">Malware</span>
        </div>
        <div class="flex items-center gap-2">
            <span
                class="w-3 h-3 rounded-full bg-phishing shadow-[0_0_12px_rgba(168,85,247,1)] ring-2 ring-phishing/50"></span>
            <span class="font-medium">Phishing</span>
        </div>
        <div class="flex items-center gap-2">
            <span
                class="w-3 h-3 rounded-full bg-exploit shadow-[0_0_12px_rgba(34,211,238,1)] ring-2 ring-exploit/50"></span>
            <span class="font-medium">Exploit</span>
        </div>
    </div>

    <script>
        function togglePanel(panelId) {
            const panel = document.getElementById(panelId);
            const isLeft = panelId.includes('left');
            const hiddenClass = isLeft ? 'left-panel-hidden' : 'right-panel-hidden';
            const iconId = isLeft ? 'left-icon' : 'right-icon';
            const icon = document.getElementById(iconId);
            const toggleButton = document.getElementById(panelId + '-toggle');
            const mapContainer = document.getElementById('map-container');

            if (panel.classList.contains(hiddenClass)) {
                panel.classList.remove(hiddenClass);
                toggleButton.title = 'Sembunyikan Panel';
                if (window.innerWidth >= 1024) {
                    if (isLeft) {
                        mapContainer.classList.remove('map-expand-left');
                    } else {
                        mapContainer.classList.remove('map-expand-right');
                    }
                }

                if (isLeft) {
                    icon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>';
                } else {
                    icon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
                }
            } else {
                // Sembunyikan Panel
                panel.classList.add(hiddenClass);
                toggleButton.title = 'Tampilkan Panel';

                // Perluas map ke sisi yang panelnya disembunyikan
                if (window.innerWidth >= 1024) {
                    if (isLeft) {
                        mapContainer.classList.add('map-expand-left');
                    } else {
                        mapContainer.classList.add('map-expand-right');
                    }
                }

                if (isLeft) {
                    icon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
                } else {
                    icon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>';
                }
            }
        }

        function toggleLeftPanel() {
            if (window.innerWidth >= 1024) {
                togglePanel('left-panel');
            }
        }

        function toggleRightPanel() {
            if (window.innerWidth >= 1024) {
                togglePanel('right-panel');
            }
        }

        // === Fungsi Pan dan Zoom Peta ===
        let scale = 1;
        let translateX = 0;
        let translateY = 0;
        let isDragging = false;
        let startX = 0;
        let startY = 0;
        let lastPinchDist = 0;

        const mapContainer = document.getElementById('map-container');
        const mapInteractLayer = document.getElementById('map-interact-layer');
        const MIN_SCALE = 1.0;
        const MAX_SCALE = 4.0;

        function updateTransform() {
            // Terapkan translasi dan skala pada map container
            mapContainer.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        }

        function restrictTranslation(newTranslateX, newTranslateY) {
            // Dapatkan dimensi kontainer peta (termasuk penyesuaian dari CSS media query)
            const rect = mapContainer.getBoundingClientRect();

            // Hitung batas maksimum terjemahan
            // Ini mencegah peta digeser terlalu jauh sehingga area kosong terlihat
            const contentWidth = rect.width;
            const contentHeight = rect.height;

            // maxPan adalah seberapa jauh konten yang di-zoom melebihi batas (dibagi 2)
            const maxPanX = Math.max(0, (contentWidth * scale - contentWidth) / 2);
            const maxPanY = Math.max(0, (contentHeight * scale - contentHeight) / 2);

            translateX = Math.max(-maxPanX, Math.min(maxPanX, newTranslateX));
            translateY = Math.max(-maxPanY, Math.min(maxPanY, newTranslateY));
        }

        function zoom(direction, center) {
            const zoomSpeed = 0.2;
            const oldScale = scale;
            const scaleFactor = 1 + (direction * zoomSpeed);

            scale *= scaleFactor;
            scale = Math.max(MIN_SCALE, Math.min(MAX_SCALE, scale));

            if (scale !== oldScale) {
                const newScaleFactor = scale / oldScale;

                // Kalkulasi translasi agar zoom berpusat pada titik 'center'
                let newTranslateX = center.x - (center.x - translateX) * newScaleFactor;
                let newTranslateY = center.y - (center.y - translateY) * newScaleFactor;

                restrictTranslation(newTranslateX, newTranslateY);
                updateTransform();
            }
        }

        function zoomIn() {
            // Zoom In terpusat di tengah layar
            const rect = mapInteractLayer.getBoundingClientRect();
            const center = {
                x: rect.width / 2,
                y: rect.height / 2
            };
            zoom(1, center);
        }

        function zoomOut() {
            // Zoom Out terpusat di tengah layar
            const rect = mapInteractLayer.getBoundingClientRect();
            const center = {
                x: rect.width / 2,
                y: rect.height / 2
            };
            zoom(-1, center);
        }

        // --- Event Listener Mouse ---
        mapInteractLayer.addEventListener('wheel', (e) => {
            e.preventDefault();
            const zoomSpeed = 0.1;
            const oldScale = scale;

            // Hitung skala baru berdasarkan arah scroll
            scale += e.deltaY * -0.01 * zoomSpeed * scale;
            scale = Math.max(MIN_SCALE, Math.min(MAX_SCALE, scale));

            if (scale !== oldScale) {
                const rect = mapInteractLayer.getBoundingClientRect();

                // Koordinat mouse relatif terhadap lapisan interaksi
                const mouseX = e.clientX - rect.left;
                const mouseY = e.clientY - rect.top;

                const scaleFactor = scale / oldScale;

                // Sesuaikan translasi agar titik di bawah kursor tetap di tempatnya
                let newTranslateX = mouseX - (mouseX - translateX) * scaleFactor;
                let newTranslateY = mouseY - (mouseY - translateY) * scaleFactor;

                restrictTranslation(newTranslateX, newTranslateY);
                updateTransform();
            }
        }, {
            passive: false
        });

        mapInteractLayer.addEventListener('mousedown', (e) => {
            // Hanya geser dengan tombol kiri mouse (primary button)
            if (e.buttons === 1) {
                isDragging = true;
                // Simpan posisi awal mouse relatif terhadap translasi peta saat ini
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
                mapInteractLayer.style.cursor = 'grabbing';
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging || e.touches) return;

            const newTranslateX = e.clientX - startX;
            const newTranslateY = e.clientY - startY;

            restrictTranslation(newTranslateX, newTranslateY);
            updateTransform();
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                mapInteractLayer.style.cursor = 'grab';
            }
        });

        // --- Event Listener Touch ---
        function getDistance(t1, t2) {
            const dx = t1.clientX - t2.clientX;
            const dy = t1.clientY - t2.clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        function getCenter(t1, t2) {
            return {
                x: (t1.clientX + t2.clientX) / 2,
                y: (t1.clientY + t2.clientY) / 2
            };
        }

        mapInteractLayer.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) { // Satu jari untuk Pan
                isDragging = true;
                startX = e.touches[0].clientX - translateX;
                startY = e.touches[0].clientY - translateY;
            } else if (e.touches.length === 2) { // Dua jari untuk Pinch Zoom
                isDragging = false;
                lastPinchDist = getDistance(e.touches[0], e.touches[1]);
            }
        }, {
            passive: false
        });

        mapInteractLayer.addEventListener('touchmove', (e) => {
            e.preventDefault();
            const rect = mapInteractLayer.getBoundingClientRect();

            if (e.touches.length === 1 && isDragging) { // Pan
                const newTranslateX = e.touches[0].clientX - startX;
                const newTranslateY = e.touches[0].clientY - startY;

                restrictTranslation(newTranslateX, newTranslateY);
                updateTransform();
            } else if (e.touches.length === 2) { // Pinch Zoom
                const newPinchDist = getDistance(e.touches[0], e.touches[1]);
                const centerTouch = getCenter(e.touches[0], e.touches[1]);

                if (lastPinchDist === 0) {
                    lastPinchDist = newPinchDist;
                    return;
                }

                // Kalkulasi perubahan skala
                const deltaScale = (newPinchDist - lastPinchDist) / 500;
                const oldScale = scale;

                scale += deltaScale;
                scale = Math.max(MIN_SCALE, Math.min(MAX_SCALE, scale));
                lastPinchDist = newPinchDist;

                if (scale !== oldScale) {
                    const scaleFactor = scale / oldScale;

                    // Hitung titik tengah sentuhan relatif terhadap lapisan interaksi
                    const mouseX = centerTouch.x - rect.left;
                    const mouseY = centerTouch.y - rect.top;

                    // Sesuaikan translasi agar zoom berpusat pada titik tengah sentuhan
                    let newTranslateX = mouseX - (mouseX - translateX) * scaleFactor;
                    let newTranslateY = mouseY - (mouseY - translateY) * scaleFactor;

                    restrictTranslation(newTranslateX, newTranslateY);
                }

                updateTransform();
            }
        }, {
            passive: false
        });

        mapInteractLayer.addEventListener('touchend', () => {
            isDragging = false;
            lastPinchDist = 0;
        });

        // === Simulasi Data dan Chart ===
        const chart = document.getElementById('attackChart')
        const ctx = chart.getContext('2d')
        let chartData = Array(40).fill(10)

        function drawChart() {
            ctx.clearRect(0, 0, chart.width, chart.height)
            const w = chart.width
            const h = chart.height
            const max = Math.max(...chartData)

            ctx.beginPath()
            chartData.forEach((v, i) => {
                const x = (i / (chartData.length - 1)) * w
                const y = h - (v / (max || 1)) * h
                i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y)
            })

            ctx.strokeStyle = '#00bfff'
            ctx.lineWidth = 2.5
            ctx.shadowColor = '#00bfff'
            ctx.shadowBlur = 15
            ctx.stroke()

            ctx.lineTo(w, h)
            ctx.lineTo(0, h)
            ctx.closePath()

            const g = ctx.createLinearGradient(0, 0, 0, h)
            g.addColorStop(0, 'rgba(0,191,255,.5)')
            g.addColorStop(1, 'rgba(0,191,255,0)')
            ctx.fillStyle = g
            ctx.fill()
        }

        setInterval(() => {
            chartData.push(Math.floor(Math.random() * 30) + 10)
            if (chartData.length > 40) chartData.shift()
            drawChart()
        }, 1000)

        window.addEventListener('load', drawChart);

        let totalAttacks = 0;
        const targetAttackCounts = {};
        const sourceAttackCounts = {};

        // === Inisialisasi Raven.js (Peta) ===
        document.getElementById('raven-iframe').addEventListener('load', function() {
            const w = this.contentWindow
            const raven = w.raven
            if (!raven) return

            // Menambahkan Style Custom untuk Peta
            const style = w.document.createElement('style')
            style.innerHTML = `
                .raven-waiting, .taskbar-panel { display:none!important }

                /* Latar belakang peta sedikit transparan */
                .world-map-svg { background-color: rgba(2, 6, 23, 0.4) !important; }

                /* Warna default negara */
                .country { fill:#0f172a!important }
                .country:hover {
                    fill:#00bfff!important;
                    filter:drop-shadow(0 0 12px rgba(0,191,255,.9))
                }

                /* Highlight saat serangan terjadi */
                .country-highlight {
                    stroke: #ff9900 !important;
                    stroke-width: 3px !important; 
                    filter: drop-shadow(0 0 10px #ff9900) drop-shadow(0 0 20px #ff990099) !important;
                    transition: all 0.2s ease-out;
                }

                /* Styling garis serangan */
                .attack-line {
                    stroke-width:2.4;
                    stroke-linecap:round;
                    filter:
                        drop-shadow(0 0 8px currentColor)
                        drop-shadow(0 0 18px currentColor);
                }

                /* Animasi titik dampak */
                .attack-circle {
                    animation: impact-core .6s ease-out;
                }

                @keyframes impact-core {
                    0% { r:4; opacity:.6 }
                    50% { r:7; opacity:1 }
                    100% { r:5; opacity:.9 }
                }

                /* Animasi cincin dampak */
                .attack-impact {
                    fill:none;
                    stroke:currentColor;
                    stroke-width:3;
                    opacity:.9;
                    animation: impact-ring 1.2s ease-out forwards;
                }

                @keyframes impact-ring {
                    0% { r:4; opacity:1; stroke-width: 3; }
                    70% { r:25; opacity:.35; stroke-width: 1; }
                    100% { r:40; opacity:0; stroke-width: 0; }
                }
            `
            w.document.head.appendChild(style)

            // Inisialisasi Raven.js
            raven.init_all({
                world_type: null,
                remove_countries: ['aq'],
                height: window.innerHeight,
                width: window.innerWidth,
                backup_background_color: 'rgba(2, 6, 23, 0.4)',
                orginal_country_color: '#0f172a',
                selected_country_color: '#00bfff',
                global_timeout: 1400,
                db_length: 4000,
                live_attacks_limit: 200,
                location: 'scripts',
                panels: ['tooltip'],
                disable: ['taskbar'],
                verbose: false
            })

            raven.init_world()

            const attackTypes = [{
                    type: 'Malware',
                    color: '#ef4444',
                    names: ['RondoDox Botnet', 'Trojan.Generic', 'Mirai Variant']
                },
                {
                    type: 'Phishing',
                    color: '#a855f7',
                    names: ['Credential Harvesting', 'Fake Login Page', 'Email Phishing']
                },
                {
                    type: 'Exploit',
                    color: '#22d3ee',
                    names: ['HTTP RCE', 'Command Injection', 'SQL Injection']
                }
            ]

            const countryMap = {
                'us': 'USA',
                'cn': 'China',
                'br': 'Brazil',
                'de': 'Germany',
                'id': 'Indonesia',
                'vn': 'Vietnam',
                'in': 'India',
                'au': 'Australia',
                'ru': 'Russia',
                'jp': 'Japan',
                'kr': 'S. Korea',
                'ca': 'Canada',
                'fr': 'France',
                'gb': 'UK',
                'es': 'Spain',
                'it': 'Italy'
            };
            const srcKeys = ['us', 'cn', 'ru', 'br', 'de', 'ca', 'fr', 'gb', 'es', 'it'];
            const dstKeys = ['id', 'vn', 'in', 'au', 'jp', 'kr']; // Target utama

            const list = document.getElementById('attack-list')
            const totalAttacksEl = document.getElementById('total-attacks');
            const topCountryEl = document.getElementById('top-country');
            const topSourcesListEl = document.getElementById('top-sources-list');

            function formatNumber(num) {
                return num.toLocaleString();
            }

            // Fungsi untuk menyorot negara target di peta
            function highlightCountry(code, duration = 500) {
                const w = document.getElementById('raven-iframe').contentWindow;
                if (!w) return;

                const countryElement = w.document.querySelector(
                    `.world-map-svg .country[data-country-code="${code}"]`);

                if (countryElement) {
                    countryElement.classList.add('country-highlight');

                    setTimeout(() => {
                        countryElement.classList.remove('country-highlight');
                    }, duration);
                }
            }

            // Fungsi untuk memperbarui metrik serangan
            function updateMetrics(targetCountryCode, sourceCountryCode) {
                totalAttacks++;

                totalAttacksEl.textContent = formatNumber(totalAttacks);

                // Hitung target yang paling sering diserang
                targetAttackCounts[targetCountryCode] = (targetAttackCounts[targetCountryCode] || 0) + 1;

                let maxTargetCount = 0;
                let topTargetCountryCode = 'N/A';
                for (const code in targetAttackCounts) {
                    if (targetAttackCounts[code] > maxTargetCount) {
                        maxTargetCount = targetAttackCounts[code];
                        topTargetCountryCode = code;
                    }
                }
                topCountryEl.textContent = maxTargetCount > 0 ? countryMap[topTargetCountryCode] ||
                    topTargetCountryCode.toUpperCase() : 'N/A';

                // Hitung sumber serangan teratas
                sourceAttackCounts[sourceCountryCode] = (sourceAttackCounts[sourceCountryCode] || 0) + 1;

                const sortedSources = Object.keys(sourceAttackCounts)
                    .map(code => ({
                        code,
                        count: sourceAttackCounts[code]
                    }))
                    .sort((a, b) => b.count - a.count)
                    .slice(0, 8);

                topSourcesListEl.innerHTML = '';

                const sourceColors = {
                    'us': '#22d3ee',
                    'cn': '#ef4444',
                    'ru': '#a855f7',
                    'br': '#22d3ee',
                    'de': '#ef4444',
                    'ca': '#a855f7',
                    'fr': '#22d3ee',
                    'gb': '#ef4444',
                    'es': '#a855f7',
                    'it': '#22d3ee'
                };

                sortedSources.forEach(({
                    code,
                    count
                }) => {
                    const countryName = countryMap[code] || code.toUpperCase();
                    const color = sourceColors[code] || '#a855f7';

                    const sourceItem = document.createElement('div');
                    sourceItem.className =
                        'p-3 bg-slate-800/40 rounded-xl flex justify-between items-center border border-white/5 drop-shadow-[0_0_8px_rgba(0,191,255,0.1)]';
                    sourceItem.innerHTML = `
                        <div class="text-sm font-mono text-exploit drop-shadow-[0_0_3px] transition duration-300" style="color: ${color};">${countryName}</div>
                        <div class="text-xs text-slate-400">${formatNumber(count)} attacks</div>
                    `;
                    topSourcesListEl.appendChild(sourceItem);
                });
            }

            // Fungsi untuk menambahkan item ke log serangan
            function addItem(t, name, from, to, color) {
                const el = document.createElement('div')
                el.className = 'transition-opacity duration-700'

                el.innerHTML = `
                    <div class="p-3 bg-slate-800/60 rounded-xl border-l-4 shadow-xl transition duration-300 hover:bg-slate-700/70 drop-shadow-[0_0_10px_rgba(0,191,255,0.3)]" style="border-color:${color}; border-right: 1px solid ${color}40; border-bottom: 1px solid ${color}40;">
                        <div class="flex justify-between items-start text-sm">
                            <div class="font-semibold text-slate-100 truncate flex-1">${from} <span class="text-slate-400 font-normal text-xs">→</span> ${to}</div>
                            <div class="text-[10px] font-mono tracking-wider drop-shadow-[0_0_5px] flex-shrink-0 ml-2 py-0.5 px-1 rounded-full border border-current" style="color:${color}; box-shadow: 0 0 10px ${color}40;">${t.toUpperCase()}</div>
                        </div>
                        <div class="text-xs text-slate-300 mt-1">
                            Attack: <span class="text-slate-200 font-mono">${name}</span>
                        </div>
                        <div class="text-[10px] text-slate-500 mt-1 text-right">
                            ${new Date().toLocaleTimeString()}
                        </div>
                    </div>
                `

                list.prepend(el)
                // Batasi jumlah log
                if (list.children.length > 8) {
                    list.lastChild.style.opacity = '0'
                    setTimeout(() => list.removeChild(list.lastChild), 700)
                }
            }

            // Fungsi untuk membuat efek cincin dampak pada titik serangan
            function impactEffect() {
                const w = document.getElementById('raven-iframe').contentWindow;
                if (!w) return;
                const svg = w.document.querySelector('svg')
                if (!svg) return
                svg.querySelectorAll('.attack-circle').forEach(c => {
                    if (c.dataset.impact) return
                    c.dataset.impact = 1
                    const cx = c.getAttribute('cx')
                    const cy = c.getAttribute('cy')
                    const color = getComputedStyle(c).color
                    // Buat dua cincin animasi
                    for (let i = 0; i < 2; i++) {
                        const ring = w.document.createElementNS('http://www.w3.org/2000/svg', 'circle')
                        ring.setAttribute('cx', cx)
                        ring.setAttribute('cy', cy)
                        ring.setAttribute('r', 4)
                        ring.setAttribute('class', 'attack-impact')
                        ring.style.stroke = color
                        ring.style.animationDelay = `${i * 0.15}s`
                        svg.appendChild(ring)
                        setTimeout(() => ring.remove(), 1300)
                    }
                })
            }

            // Fungsi utama untuk memicu serangan baru
            function fire() {
                const t = attackTypes[Math.floor(Math.random() * attackTypes.length)]
                const name = t.names[Math.floor(Math.random() * t.names.length)]

                const srcCode = srcKeys[Math.floor(Math.random() * srcKeys.length)];
                // 80% kemungkinan menyerang target utama, 20% menyerang sumber lain
                const targetPool = Math.random() < 0.8 ? dstKeys : srcKeys;
                const dstCode = targetPool[Math.floor(Math.random() * targetPool.length)];

                // Pastikan sumber dan target berbeda
                if (srcCode === dstCode) return fire();

                const fromName = countryMap[srcCode];
                const toName = countryMap[dstCode];

                // Tambahkan serangan ke peta Raven.js
                raven.add_to_data_to_table({
                        from: srcCode,
                        to: dstCode
                    }, {
                        line: {
                            from: t.color,
                            to: t.color
                        }
                    },
                    1200,
                    ['line']
                )

                // Perbarui UI
                addItem(t.type, name, fromName, toName, t.color)
                updateMetrics(dstCode, srcCode);

                highlightCountry(dstCode);
                setTimeout(impactEffect, 100)
            }

            // Mulai simulasi serangan
            setTimeout(() => {
                fire()
                setInterval(fire, 300) // Pemicu serangan setiap 300ms
            }, 600)
        })
    </script>

</body>

</html>
