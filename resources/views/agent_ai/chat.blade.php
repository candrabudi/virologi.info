<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virologi | Intelligent Interface</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter+Tight:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --bg-color: #050505;
            --card-bg: rgba(20, 20, 20, 0.6);
            --accent-primary: #ffffff;
            --border-glow: rgba(255, 255, 255, 0.08);
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        body {
            font-family: 'Inter Tight', sans-serif;
            background-color: var(--bg-color);
            color: #fafafa;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        .ambient-light {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 50% -20%, rgba(59, 130, 246, 0.1), transparent 60%),
                radial-gradient(circle at 0% 100%, rgba(139, 92, 246, 0.05), transparent 40%);
            z-index: -1;
            pointer-events: none;
        }

        /* Sidebar State */
        #sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background: var(--card-bg);
            backdrop-filter: blur(40px);
            border-right: 1px solid var(--border-glow);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 100;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            min-width: var(--sidebar-collapsed-width);
        }

        #sidebar.collapsed .sidebar-content-full {
            opacity: 0;
            display: none;
        }

        #sidebar.collapsed .sidebar-content-mini {
            display: flex;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                height: 100%;
                left: -100%;
                width: 85% !important;
                min-width: 85% !important;
            }

            #sidebar.mobile-open {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                z-index: 90;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Bento Grid */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .bento-item {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-glow);
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .bento-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Chat Messages */
        .user-message {
            background: #111;
            border: 1px solid var(--border-glow);
            padding: 12px 18px;
            border-radius: 18px;
            margin-left: auto;
            max-width: 85%;
            font-size: 14.5px;
        }

        .ai-message {
            animation: messageAppear 0.4s ease-out;
        }

        /* Dynamic Input Styling - Fixed Alignment */
        .input-container {
            padding: 1.5rem;
            background: linear-gradient(to top, var(--bg-color) 80%, transparent);
        }

        .input-wrapper {
            background: rgba(18, 18, 18, 0.8);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 22px;
            max-width: 52rem;
            margin: 0 auto;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            /* Menjaga semua item tetap sejajar di tengah secara vertikal */
            padding: 0.5rem 0.75rem;
        }

        .input-wrapper:focus-within {
            border-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.05);
        }

        #chat-input {
            max-height: 200px;
            background: transparent;
            border: none;
            outline: none;
            resize: none;
            width: 100%;
            font-size: 15px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            line-height: 1.5;
            color: #fafafa;
        }

        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .custom-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .ai-text {
            color: #e5e7eb;
            line-height: 1.75;
            font-size: 15px;
            margin: 6px 0;
        }

        .ai-code-canvas {
            background: #0b0f14;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
            overflow: hidden;
            margin: 14px 0;
        }

        .ai-code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 14px;
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #9ca3af;
            background: rgba(0, 0, 0, .45);
        }

        .ai-code-header button {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
        }

        .ai-code-header button:hover {
            color: white;
        }

        .ai-code-canvas pre {
            margin: 0;
            padding: 16px;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.65;
            color: #e5e7eb;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }
    </style>
    <style>
        .thinking-dots span {
            animation: blink 1.4s infinite both;
        }

        .thinking-dots span:nth-child(2) {
            animation-delay: .2s;
        }

        .thinking-dots span:nth-child(3) {
            animation-delay: .4s;
        }

        @keyframes blink {
            0% {
                opacity: .2;
            }

            20% {
                opacity: 1;
            }

            100% {
                opacity: .2;
            }
        }
    </style>

</head>

<body>

    <div class="ambient-light"></div>
    <div id="overlay" class="sidebar-overlay" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="flex flex-col h-full overflow-hidden">
        <div class="p-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3 sidebar-content-full">
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center">
                    <i class="fas fa-bolt text-[10px] text-black"></i>
                </div>
                <span class="font-bold text-lg tracking-tight">Virologi</span>
            </div>

            <div class="hidden sidebar-content-mini w-full flex-col items-center">
                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-white/10 transition-colors cursor-pointer"
                    onclick="toggleSidebar()">
                    <i class="fas fa-bolt text-xs"></i>
                </div>
            </div>

            <button onclick="toggleSidebar()"
                class="hidden md:flex sidebar-content-full text-gray-500 hover:text-white transition-colors">
                <i class="fas fa-bars-staggered"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-4 sidebar-content-full custom-scroll space-y-6 pt-4">
            <button onclick="createNewChat()"
                class="w-full py-3 px-4 rounded-xl bg-white text-black font-semibold text-sm hover:opacity-90 transition-all flex items-center justify-center gap-2 mb-6">
                <i class="fas fa-plus text-[10px]"></i> Chat Baru
            </button>

            <div id="session-list" class="space-y-1"></div>
        </div>

        <div class="hidden sidebar-content-mini flex-col items-center gap-6 pb-8 pt-4">
            <button onclick="createNewChat()"
                class="w-10 h-10 rounded-full bg-white text-black flex items-center justify-center hover:scale-110 transition-transform">
                <i class="fas fa-plus text-xs"></i>
            </button>

            <button class="text-gray-500 hover:text-white">
                <i class="fas fa-clock-rotate-left"></i>
            </button>

            <button class="text-gray-500 hover:text-white" onclick="toggleLogoutMini()">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>


        <div class="p-4 border-t border-white/5 sidebar-content-full">
            <div class="group relative bg-white/5 p-3 rounded-2xl transition-all hover:bg-white/[0.08]">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 flex items-center justify-center font-bold text-xs">
                        AD</div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-xs font-bold truncate">{{ Auth::user()->detail->full_name }}</p>
                        <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <button onclick="handleLogout()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-red-500/20 text-gray-500 hover:text-red-400 transition-all"
                        title="Logout">
                        <i class="fas fa-power-off text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col h-full relative overflow-hidden">
        <header class="h-20 flex items-center justify-between px-6 md:px-10 border-b border-white/5 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <button onclick="toggleMobileSidebar()"
                    class="md:hidden w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/5">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                <div class="hidden md:flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Model:</span>
                    <span
                        class="text-xs font-bold text-white px-2 py-1 rounded bg-white/10 tracking-tight">Virologi-o1_Preview</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="flex items-center gap-2 bg-emerald-500/10 text-emerald-400 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Sistem Optimal
                </div>
            </div>
        </header>

        <div id="chat-viewport" class="flex-1 overflow-y-auto px-4 md:px-10 py-10 custom-scroll">
            <div class="max-w-4xl mx-auto w-full">
                <div id="welcome-view" class="mt-10 mb-20">
                    <h1 class="text-4xl md:text-6xl font-bold tracking-tighter mb-6 leading-[1.1]">
                        Apa yang ingin kamu pelajari<br>
                        <span class="text-gray-500">tentang Cyber Security & Coding hari ini?</span>
                    </h1>

                    <div class="bento-grid">
                        <div onclick="triggerPrompt('Jelaskan konsep dasar cyber security untuk pemula')"
                            class="bento-item">
                            <i class="fas fa-shield-halved text-indigo-400 mb-4 text-lg"></i>
                            <h3 class="font-bold text-sm mb-1">Dasar Cyber Security</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Pelajari fundamental keamanan: threat, vulnerability, dan attack vector.
                            </p>
                        </div>

                        <div onclick="triggerPrompt('Analisis keamanan kode ini dan jelaskan potensi celahnya')"
                            class="bento-item">
                            <i class="fas fa-code text-emerald-400 mb-4 text-lg"></i>
                            <h3 class="font-bold text-sm mb-1">Analisis Kode Aman</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Deteksi bug, SQL injection, XSS, dan praktik coding yang aman.
                            </p>
                        </div>

                        <div onclick="triggerPrompt('Bagaimana cara mencegah DDoS dan brute force attack di server?')"
                            class="bento-item">
                            <i class="fas fa-server text-rose-400 mb-4 text-lg"></i>
                            <h3 class="font-bold text-sm mb-1">Proteksi Server</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Strategi firewall, rate limiting, dan hardening server.
                            </p>
                        </div>

                        <div onclick="triggerPrompt('Buatkan roadmap belajar programming untuk cyber security')"
                            class="bento-item">
                            <i class="fas fa-road text-amber-400 mb-4 text-lg"></i>
                            <h3 class="font-bold text-sm mb-1">Roadmap Belajar</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Panduan step-by-step coding: backend, security, hingga pentest.
                            </p>
                        </div>
                    </div>
                </div>

                <div id="message-container" class="space-y-12"></div>
            </div>
        </div>

        <div class="input-container">
            <div class="input-wrapper">
                <button type="button"
                    class="w-10 h-10 rounded-xl hover:bg-white/5 text-gray-500 transition-colors flex items-center justify-center flex-shrink-0"
                    title="Lampirkan file">
                    <i class="fas fa-paperclip text-sm"></i>
                </button>

                <form id="chat-form" class="flex-1 flex items-center">
                    <textarea id="chat-input" rows="1" placeholder="Kirim pesan ke Cyber Security Assistant..."
                        class="custom-scroll" oninput="adjustInputHeight(this)"></textarea>

                    <button type="submit" id="send-btn"
                        class="w-10 h-10 rounded-xl bg-white text-black flex items-center justify-center hover:bg-gray-200 active:scale-90 transition-all disabled:opacity-30 disabled:pointer-events-none flex-shrink-0 ml-2">
                        <i class="fas fa-arrow-up text-xs"></i>
                    </button>
                </form>
            </div>

            <p class="text-[9px] text-center text-gray-600 mt-4 uppercase tracking-[0.4em] font-medium opacity-60">
                Virologi v2.5 • AI Assistant 2025</p>
        </div>
    </main>

    <div id="logout-modal"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div class="bg-[#111] border border-white/10 p-8 rounded-3xl max-w-sm w-full text-center">
            <div
                class="w-16 h-16 bg-red-500/10 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-xl">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h2 class="text-xl font-bold mb-2">Konfirmasi Logout</h2>
            <p class="text-gray-500 text-sm mb-8">Apakah Anda yakin ingin mengakhiri sesi ini? Semua progres yang belum
                disimpan mungkin hilang.</p>
            <div class="flex gap-3">
                <button onclick="closeLogoutModal()"
                    class="flex-1 py-3 rounded-xl bg-white/5 text-sm font-bold hover:bg-white/10 transition-colors">Batal</button>
                <button onclick="executeLogout()"
                    class="flex-1 py-3 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors">Logout
                    Sekarang</button>
            </div>
        </div>
    </div>
    <div id="delete-chat-modal"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[300] hidden items-center justify-center p-6">
        <div class="bg-[#111] border border-white/10 p-8 rounded-3xl max-w-sm w-full text-center">
            <div
                class="w-16 h-16 bg-red-500/10 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-trash"></i>
            </div>
            <h2 class="text-xl font-bold mb-2">Hapus Chat</h2>
            <p class="text-gray-500 text-sm mb-8">
                Chat ini akan dihapus permanen dan tidak dapat dikembalikan.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-sm font-bold">
                    Batal
                </button>
                <button onclick="confirmDeleteChat()"
                    class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-sm font-bold text-white">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let activeSession = null
        let isCreatingSession = false
        let thinkingEl = null
        let deleteTargetToken = null
        let openMenuToken = null

        const sessionList = document.getElementById('session-list')
        const chatForm = document.getElementById('chat-form')
        const chatInput = document.getElementById('chat-input')
        const messageContainer = document.getElementById('message-container')
        const welcomeView = document.getElementById('welcome-view')
        const viewport = document.getElementById('chat-viewport')
        const sendBtn = document.getElementById('send-btn')
        const sidebar = document.getElementById('sidebar')
        const overlay = document.getElementById('overlay')
        const logoutModal = document.getElementById('logout-modal')
        const deleteModal = document.getElementById('delete-chat-modal')

        const csrfMeta = document.querySelector('meta[name="csrf-token"]')
        if (csrfMeta && window.axios) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content')
        }

        function getTokenFromUrl() {
            const p = location.pathname.split('/')
            return p.length > 3 ? p[3] : null
        }

        function setUrl(token) {
            history.pushState({
                token
            }, '', `/ai-agent/chat/${token}`)
        }

        function escapeHtml(s) {
            return String(s).replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            } [m]))
        }

        function linkify(s) {
            return s.replace(/(https?:\/\/[^\s<]+)/g, u =>
                `<a href="${u}" target="_blank" class="text-sky-300 underline underline-offset-4">${u}</a>`
            )
        }

        function normalizeAiResponse(raw) {
            if (typeof raw !== 'string') return raw
            return raw
                .replace(/\r/g, '')
                .replace(/\\n/g, '\n')
                .replace(/\\t/g, '\t')
                .replace(/\\\\/g, '\\')
        }

        function stripLeakedCode(raw) {
            if (typeof raw !== 'string') return raw

            const fenceIndex = raw.indexOf('```')
        if (fenceIndex === -1) return raw

        const before = raw.slice(0, fenceIndex)
        const looksLikeCode =
            before.includes('<?php') ||
        before.includes('namespace ') ||
        before.includes('class ') ||
        before.includes('function ') ||
        (before.includes('{') && before.includes('}'))

    return looksLikeCode ? raw.slice(fenceIndex) : raw
}

function copyCode(btn) {
    const code = btn.closest('.ai-code-canvas')
        ?.querySelector('pre code')
        ?.innerText

    if (!code) return

    navigator.clipboard.writeText(code).then(() => {
        btn.innerText = 'Copied'
        setTimeout(() => btn.innerText = 'Copy', 1500)
    })
}

function renderAssistant(raw) {
    if (!raw) return ''

    raw = stripLeakedCode(normalizeAiResponse(raw))
    const parts = raw.split(/```/g)
        let html = ''

        parts.forEach((block, index) => {
            if (index % 2 === 1) {
                const lines = block.split('\n')
                const lang = (lines.shift() || 'code').trim()
                const code = lines.join('\n').trim()

                html += `
<div class="ai-code-canvas">
    <div class="ai-code-header">
        <span>${escapeHtml(lang.toUpperCase())}</span>
        <button onclick="copyCode(this)">Copy</button>
    </div>
    <pre><code>${escapeHtml(code)}</code></pre>
</div>`
            } else {
                block.split('\n').forEach(line => {
                    const t = line.trim()
                    if (!t) return
                    html += `
<p class="ai-text">
    ${linkify(escapeHtml(t).replace(/\*\*(.+?)\*\*/g, '<b>$1</b>'))}
</p>`
                })
            }
        })

        return html
    }

    function scrollBottom() {
        viewport.scrollTo({ top: viewport.scrollHeight, behavior: 'smooth' })
    }

    function appendUser(content) {
        welcomeView.style.display = 'none'
        const el = document.createElement('div')
        el.className = 'flex justify-end'
        el.innerHTML = `<div class="user-message">${escapeHtml(content)}</div>`
        messageContainer.appendChild(el)
        scrollBottom()
    }

    function appendAssistant(content) {
        const el = document.createElement('div')
        el.className = 'ai-message flex gap-5'
        el.innerHTML = `
<div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
    <i class="fas fa-shield-halved text-black text-xs"></i>
</div>
<div class="flex-1">
    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-3">
        Cyber Security Assistant
    </p>
    <div class="space-y-3 text-[15px]">
        ${renderAssistant(content)}
    </div>
</div>`
        messageContainer.appendChild(el)
        scrollBottom()
    }

    function appendThinking() {
        const el = document.createElement('div')
        el.className = 'ai-message flex gap-5'
        el.innerHTML = `
<div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
    <i class="fas fa-spinner fa-spin text-xs text-white/70"></i>
</div>
<div class="flex-1">
    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-3">
        Cyber Security Assistant
    </p>
    <div class="text-gray-400 italic">Menganalisis permintaan keamanan...</div>
</div>`
        messageContainer.appendChild(el)
        thinkingEl = el
        scrollBottom()
    }

    function removeThinking() {
        if (thinkingEl) thinkingEl.remove()
        thinkingEl = null
    }

    function closeAllSessionMenus() {
        document.querySelectorAll('[data-session-menu]')
            .forEach(m => m.classList.add('hidden'))
        openMenuToken = null
    }

    function toggleSessionMenu(e, token) {
        e.stopPropagation()
        const menu = document.querySelector(`[data-session-menu="${token}"]`)
        if (!menu) return

        if (openMenuToken && openMenuToken !== token) closeAllSessionMenus()

        const hidden = menu.classList.contains('hidden')
        closeAllSessionMenus()

        if (hidden) {
            menu.classList.remove('hidden')
            openMenuToken = token
        }
    }

    function loadSessions() {
        axios.get('/ai-agent/sessions').then(res => {
            sessionList.innerHTML = ''
            const items = (res.data || []).slice().sort(
                (a, b) => (b.is_pinned ? 1 : 0) - (a.is_pinned ? 1 : 0)
            )

            items.forEach(s => {
                const row = document.createElement('div')
                row.className = `px-4 py-3 rounded-lg cursor-pointer text-sm ${
                activeSession === s.session_token
                    ? 'bg-white/10 text-white'
                    : 'text-gray-400 hover:text-white hover:bg-white/5'
            }`

                row.innerHTML = `
<div class="flex items-center justify-between gap-3">
    <div class="truncate flex-1">${escapeHtml(s.title || 'Percakapan Baru')}</div>
    <div class="relative flex-shrink-0">
        <button class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center"
            onclick="toggleSessionMenu(event,'${s.session_token}')">
            <i class="fas fa-ellipsis text-xs"></i>
        </button>
        <div data-session-menu="${s.session_token}"
            class="hidden absolute right-0 mt-2 w-40 bg-[#111] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-[60]">
            <button class="w-full px-3 py-2 text-left text-xs hover:bg-white/5"
                onclick="pinSession(event,'${s.session_token}')">
                ${s.is_pinned ? 'Unpin Chat' : 'Pin Chat'}
            </button>
            <button class="w-full px-3 py-2 text-left text-xs text-red-400 hover:bg-red-500/10"
                onclick="openDeleteModal(event,'${s.session_token}')">
                Hapus Chat
            </button>
        </div>
    </div>
</div>`
                row.onclick = () => openSession(s.session_token)
                sessionList.appendChild(row)
            })
        })
    }

    function openSession(token, push = true) {
        closeAllSessionMenus()
        activeSession = token
        isCreatingSession = false
        messageContainer.innerHTML = ''
        welcomeView.style.display = 'none'
        if (push) setUrl(token)
        loadSessions()

        axios.get(`/ai-agent/sessions/${token}`).then(res => {
            messageContainer.innerHTML = ''
            welcomeView.style.display = 'none'
            res.data.messages.forEach(m =>
                m.role === 'user'
                    ? appendUser(m.content)
                    : appendAssistant(m.content)
            )
        })
    }

    function createNewChat() {
        if (isCreatingSession) return Promise.resolve()
        isCreatingSession = true

        return axios.post('/ai-agent/sessions')
            .then(r => {
                activeSession = r.data.session_token
                setUrl(activeSession)
                messageContainer.innerHTML = ''
                welcomeView.style.display = 'block'
                loadSessions()
            })
            .finally(() => isCreatingSession = false)
    }

    function sendMessage(text) {
        appendUser(text)
        appendThinking()
        sendBtn.disabled = true

        axios.post(`/ai-agent/sessions/${activeSession}/message`, { content: text })
            .then(res => {
                removeThinking()
                appendAssistant(res.data.content)
                loadSessions()
            })
            .catch(err => {
                removeThinking()
                appendAssistant(err.response?.data?.content || 'Permintaan tidak dapat diproses.')
            })
            .finally(() => {
                sendBtn.disabled = false
            })
    }

    chatForm.addEventListener('submit', async e => {
        e.preventDefault()

        const text = chatInput.value.trim()
        if (!text) return

        chatInput.value = ''

        if (!activeSession) {
            await createNewChat()
        }

        sendMessage(text)
    })

    chatInput.addEventListener('input', () => {
        sendBtn.disabled = !chatInput.value.trim()
    })
    </script>

    <script>
        function adjustInputHeight(el) {
            el.style.height = 'auto'
            el.style.height = Math.min(el.scrollHeight, 240) + 'px'
        }

        function pinSession(e, token) {
            e.stopPropagation()
            closeAllSessionMenus()
            axios.post(`/ai-agent/sessions/${token}/pin`).then(loadSessions)
        }

        function openDeleteModal(e, token) {
            e.stopPropagation()
            closeAllSessionMenus()
            deleteTargetToken = token
            deleteModal.classList.remove('hidden')
            deleteModal.classList.add('flex')
        }

        function closeDeleteModal() {
            deleteTargetToken = null
            deleteModal.classList.add('hidden')
        }

        function confirmDeleteChat() {
            if (!deleteTargetToken) return
            axios.delete(`/ai-agent/sessions/${deleteTargetToken}`).then(() => {
                if (activeSession === deleteTargetToken) {
                    activeSession = null
                    history.pushState({}, '', `/ai-agent/chat`)
                    messageContainer.innerHTML = ''
                    welcomeView.style.display = 'block'
                }
                loadSessions()
                closeDeleteModal()
            })
        }

        function toggleSidebar() {
            sidebar.classList.toggle('collapsed')
        }

        function toggleMobileSidebar() {
            sidebar.classList.toggle('mobile-open')
            overlay.classList.toggle('active')
        }

        function handleLogout() {
            logoutModal.style.display = 'flex'
        }

        function closeLogoutModal() {
            logoutModal.style.display = 'none'
        }

        function executeLogout() {
            axios.post('/logout').finally(() => location.href = '/ai-agent/login')
        }

        window.addEventListener('popstate', e => {
            if (e.state?.token) openSession(e.state.token, false)
        })

        document.addEventListener('click', e => {
            if (!e.target.closest('[data-session-menu]') && !e.target.closest('button')) closeAllSessionMenus()
        })

        viewport.addEventListener('scroll', closeAllSessionMenus, {
            passive: true
        })

        document.addEventListener('DOMContentLoaded', () => {
            const token = getTokenFromUrl()
            loadSessions()
            if (token) openSession(token, false)
            sendBtn.disabled = true
        })
    </script>

</body>

</html>
