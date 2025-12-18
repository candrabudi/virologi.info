<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Virologi | Register Core</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg-dark: #080808;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
            --accent-glow: rgba(139, 92, 246, 0.15);
        }

        /* Sembunyikan scrollbar global */
        * {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        *::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, and Opera */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
            margin: 0;
            min-height: 100vh;
            min-height: -webkit-fill-available;
            display: flex;
            overflow-x: hidden;
        }

        /* Smooth Hide Spinner */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Visual Side - Desktop Only */
        .visual-side {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;
            background: #000;
            display: none;
        }

        @media (min-width: 768px) {
            .visual-side {
                display: flex;
                width: 45%;
                border-right: 1px solid var(--border);
            }
        }

        @media (min-width: 1024px) {
            .visual-side {
                width: 55%;
            }
        }

        .glow-sphere {
            position: absolute;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.04) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            animation: float 25s infinite alternate ease-in-out;
        }

        @keyframes float {
            from {
                transform: translate(-10%, -10%) scale(1);
            }

            to {
                transform: translate(15%, 15%) scale(1.1);
            }
        }

        .shimmer {
            background: linear-gradient(90deg, #333 0%, #fff 50%, #333 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            color: transparent;
            animation: shine 5s linear infinite;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        /* Auth Side & Mobile Responsiveness */
        .auth-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2.5rem 1.5rem;
            background: radial-gradient(circle at top right, var(--accent-glow), transparent 40%), #0a0a0a;
            position: relative;
            z-index: 10;
            min-height: 100vh;
            overflow-y: auto;
        }

        .scroll-container {
            width: 100%;
            max-width: 380px;
            margin: auto 0;
        }

        /* Sweet Input Style */
        .auth-input {
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 18px 22px;
            width: 100%;
            color: white;
            outline: none;
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            font-size: 15px;
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
        }

        .auth-input:focus {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            transform: scale(1.01);
        }

        .auth-input::placeholder {
            color: #444;
            transition: color 0.3s;
        }

        .auth-input:focus::placeholder {
            color: #666;
        }

        /* Premium Button */
        .btn-action {
            background: #fff;
            color: #000;
            padding: 20px;
            border-radius: 22px;
            font-weight: 800;
            width: 100%;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            border: none;
            font-size: 15px;
            letter-spacing: -0.02em;
        }

        .btn-action:active {
            transform: scale(0.96);
        }

        .btn-action:hover:not(:disabled) {
            background: #f0f0f0;
            box-shadow: 0 15px 35px -5px rgba(255, 255, 255, 0.15);
        }

        .noise-overlay {
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
            z-index: 100;
        }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            body {
                overflow-y: auto;
            }

            .auth-container {
                padding: 2rem 1.25rem;
                justify-content: flex-start;
                height: auto;
                min-height: 100vh;
            }

            .scroll-container {
                margin: 0;
            }

            h2 {
                font-size: 1.875rem !important;
            }

            .auth-input {
                padding: 16px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="noise-overlay"></div>

    <!-- Desktop Visual -->
    <div class="visual-side flex-col items-center justify-center p-20">
        <div class="glow-sphere" style="top: -5%; left: -5%;"></div>
        <div class="glow-sphere"
            style="bottom: -5%; right: -5%; animation-delay: -7s; background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);">
        </div>

        <div class="relative z-10 space-y-8 text-center md:text-left">
            <div class="w-16 h-1 bg-white/10 rounded-full mx-auto md:mx-0"></div>
            <h1 class="text-7xl lg:text-8xl font-bold tracking-tighter leading-none shimmer"
                style="font-family: 'Space Grotesk', sans-serif;">
                CORE<br>IDENTITY
            </h1>
            <p class="text-gray-500 text-lg max-w-sm font-medium leading-relaxed">
                Rancang profil digital Anda dalam ekosistem terenkripsi Nexus.
            </p>
        </div>
    </div>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="scroll-container">

            <!-- Logo -->
            <div class="flex items-center justify-center md:justify-start gap-4 mb-12 group cursor-pointer">
                <div
                    class="w-11 h-11 bg-white rounded-[18px] flex items-center justify-center shadow-[0_0_30px_rgba(255,255,255,0.15)] transition-transform group-hover:rotate-12">
                    <i class="fas fa-bolt text-black text-base"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg tracking-tighter leading-none"
                        style="font-family: 'Space Grotesk', sans-serif;">VIROLOGI</span>
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-600">Secure Protocol</span>
                </div>
            </div>

            <div class="mb-10 text-center md:text-left">
                <h2 class="text-3xl font-bold tracking-tight mb-2">Registrasi</h2>
                <p class="text-gray-500 text-sm font-medium">Lengkapi data untuk otorisasi akses.</p>
            </div>

            <form onsubmit="handleRegister(event)" class="space-y-5">

                <div class="group space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-700 ml-1">
                        Nama Lengkap
                    </label>
                    <input name="full_name" type="text" placeholder="Masukkan nama" class="auth-input" required
                        autofocus>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="group space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-700 ml-1">
                            Username
                        </label>
                        <input name="username" type="text" placeholder="id_user" class="auth-input" required>
                    </div>

                    <div class="group space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-700 ml-1">
                            WhatsApp
                        </label>
                        <input name="phone_number" type="tel" inputmode="numeric" placeholder="08..."
                            class="auth-input" required>
                    </div>
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-700 ml-1">
                        Alamat Email
                    </label>
                    <input name="email" type="email" placeholder="nama@email.com" class="auth-input" required>
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-700 ml-1">
                        Kata Sandi
                    </label>
                    <input name="password" type="password" placeholder="••••••••" class="auth-input" required>
                </div>

                <div class="pt-6">
                    <button type="submit" id="btn-register" class="btn-action w-full">
                        <span>Konfirmasi & Daftar</span>
                        <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                    </button>
                </div>
            </form>


            <div class="mt-10 text-center">
                <p class="text-xs text-gray-500 font-medium">
                    Sudah punya akses?
                    <a href="#"
                        class="text-white font-bold hover:underline underline-offset-4 decoration-gray-600">Login
                        Sekarang</a>
                </p>
            </div>

            <!-- Footer Info -->
            <div class="mt-20 flex flex-col items-center md:items-start gap-4 opacity-30 pb-10">
                <div class="h-px w-12 bg-white/20"></div>
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-center md:text-left">
                    Verifikasi 256-Bit SSL<br>
                    &copy; 2025 Virologi Core
                </p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        function handleRegister(e) {
            e.preventDefault()

            const form = e.target
            const btn = document.getElementById('btn-register')

            btn.disabled = true
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...'

            const data = new FormData(form)

            axios.post('/ai-agent/register', data)
                .then(res => {
                    window.location.href = res.data.data.redirect
                })
                .catch(err => {
                    const msg = err.response?.data?.message || 'Registrasi gagal'
                    alert(msg)
                })
                .finally(() => {
                    btn.disabled = false
                    btn.innerHTML = `
                <span>Konfirmasi & Daftar</span>
                <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
            `
                })
        }
    </script>

</body>

</html>
