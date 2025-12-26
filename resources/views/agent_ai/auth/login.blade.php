<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Virologi | Secure Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --accent: #ffffff;
            --bg-dark: #080808;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        /* Hilangkan spinner untuk Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hilangkan spinner untuk Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Visual Side - Desktop Only */
        .visual-side {
            position: relative;
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
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            animation: float 25s infinite alternate;
        }

        @keyframes float {
            from {
                transform: translate(-15%, -15%);
            }

            to {
                transform: translate(25%, 25%);
            }
        }

        /* Form Interaction Side */
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-[#0a0a0a];
            position: relative;
            z-index: 10;
        }

        /* Input Styling */
        .auth-input {
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px 20px;
            width: 100%;
            color: white;
            outline: none;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            font-size: 16px;
        }

        .auth-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 8px rgba(255, 255, 255, 0.02);
            transform: translateY(-1px);
        }

        /* OTP Grid - Tanpa Spinner */
        .otp-box {
            width: 100%;
            aspect-ratio: 1 / 1.15;
            max-width: 75px;
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Button Enhancement */
        .btn-action {
            background: #fff;
            color: #000;
            padding: 18px;
            border-radius: 18px;
            font-weight: 800;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            border: none;
            font-size: 15px;
            letter-spacing: -0.01em;
        }

        .btn-action:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(255, 255, 255, 0.15);
        }

        .btn-action:disabled {
            background: #222;
            color: #555;
            cursor: not-allowed;
        }

        /* Animations */
        .shimmer {
            background: linear-gradient(90deg, #444 0%, #fff 50%, #444 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            color: transparent;
            animation: shine 6s linear infinite;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        .view-transition {
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .hidden-view {
            display: none;
            opacity: 0;
            transform: translateY(15px);
        }

        .noise-overlay {
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.02;
            pointer-events: none;
            z-index: 100;
        }
    </style>
</head>

<body class="bg-[#080808]">
    <div id="toast"
        class="fixed top-6 right-6 z-[9999] hidden min-w-[260px] px-5 py-4 rounded-xl text-sm font-semibold shadow-2xl backdrop-blur
            transition-all duration-300">
    </div>

    <div class="noise-overlay"></div>

    <!-- Desktop Visual -->
    <div class="visual-side flex-col items-center justify-center p-20">
        <div class="glow-sphere" style="top: -5%; left: -5%;"></div>
        <div class="glow-sphere"
            style="bottom: -5%; right: -5%; animation-delay: -7s; background: radial-gradient(circle, rgba(139, 92, 246, 0.08) 0%, transparent 70%);">
        </div>

        <div class="relative z-10 space-y-8">
            <div class="w-16 h-1 bg-white/20 rounded-full"></div>
            <h1 class="text-7xl lg:text-8xl font-bold tracking-tighter leading-none shimmer"
                style="font-family: 'Space Grotesk', sans-serif;">
                CORE<br>ACCESS
            </h1>
            <p class="text-gray-500 text-lg max-w-sm font-medium leading-relaxed">
                Gerbang utama untuk orkestrasi data tingkat lanjut dan keamanan sistem terpusat.
            </p>
        </div>
    </div>

    <!-- Responsive Auth Container -->
    <div class="auth-container">
        <div class="w-full max-w-[340px] sm:max-w-sm">

            <!-- Logo -->
            <div class="flex items-center justify-center md:justify-start gap-3 mb-12">
                <div
                    class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center shadow-[0_0_25px_rgba(255,255,255,0.2)]">
                    <i class="fas fa-bolt text-black text-sm"></i>
                </div>
                <span class="font-bold text-xl tracking-tighter"
                    style="font-family: 'Space Grotesk', sans-serif;">VIROLOGI</span>
            </div>

            <!-- Login View -->
            <div id="login-view" class="view-transition">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-bold tracking-tight mb-2">Selamat Datang</h2>
                    <p class="text-gray-500 text-sm">Masuk untuk mengakses terminal kendali.</p>
                </div>

                <form onsubmit="toOTP(event)" class="space-y-5">
                    <div class="group space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-600 ml-1">
                            Identitas
                        </label>
                        <input name="identity" type="text" placeholder="Username / Email" class="auth-input" required
                            autofocus>
                    </div>

                    <div class="group space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-600 ml-1">
                            Kunci Sandi
                        </label>
                        <input name="password" type="password" placeholder="••••••••" class="auth-input" required>
                    </div>

                    <div class="pt-6">
                        <button type="submit" id="btn-login" class="btn-action">
                            <span>Lanjutkan</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </form>
                <div class="mt-12 text-center">
                    <p class="text-xs text-gray-500 font-medium">
                        Belum memiliki identitas?
                        <a href="{{ route('ai.register') }}"
                            class="text-white font-bold hover:underline underline-offset-4 decoration-gray-600">Daftar
                            Akun Baru</a>
                    </p>
                </div>

            </div>

            <!-- OTP View (Tanpa Scroll/Spinner) -->
            <div id="otp-view" class="view-transition hidden-view">
                <div class="mb-10 text-center md:text-left">
                    <button onclick="toLogin()"
                        class="text-gray-500 hover:text-white transition-colors text-[10px] font-black uppercase tracking-widest flex items-center justify-center md:justify-start gap-2 mb-8">
                        <i class="fas fa-chevron-left text-[8px]"></i> Kembali
                    </button>
                    <h2 class="text-3xl font-bold tracking-tight mb-2">Verifikasi</h2>
                    <p class="text-gray-500 text-sm">Kode dikirim ke perangkat terdaftar.</p>
                </div>

                <form onsubmit="handleVerify(event)" class="space-y-12">
                    <div class="flex justify-between gap-2 sm:gap-4">
                        <input type="text" inputmode="numeric" maxlength="1" class="auth-input otp-box"
                            id="o1" oninput="focusNext(this, 'o2')">
                        <input type="text" inputmode="numeric" maxlength="1" class="auth-input otp-box"
                            id="o2" oninput="focusNext(this, 'o3')">
                        <input type="text" inputmode="numeric" maxlength="1" class="auth-input otp-box"
                            id="o3" oninput="focusNext(this, 'o4')">
                        <input type="text" inputmode="numeric" maxlength="1" class="auth-input otp-box"
                            id="o4" oninput="focusNext(this, 'o5')">
                        <input type="text" inputmode="numeric" maxlength="1" class="auth-input otp-box"
                            id="o5" oninput="focusNext(this, 'o6')">
                        <input type="text" inputmode="numeric" maxlength="1" class="auth-input otp-box"
                            id="o6" oninput="focusNext(this, '')">
                    </div>

                    <div class="space-y-4">
                        <button type="submit" id="btn-verify" class="btn-action">
                            <span>Verifikasi Akun</span>
                        </button>
                        <p class="text-center text-[10px] text-gray-700 font-bold uppercase tracking-widest">
                            <button type="button" id="btn-resend" onclick="resendOtp()"
                                class="text-white disabled:text-gray-600 transition-colors" disabled>
                                Kirim ulang dalam <span id="resend-timer">00:45</span>
                            </button>
                        </p>

                    </div>
                </form>
            </div>


            <!-- Success State -->
            <div id="success-view" class="view-transition hidden-view text-center">
                <div class="relative w-24 h-24 mx-auto mb-10">
                    <div class="absolute inset-0 bg-white/20 rounded-full blur-3xl animate-pulse"></div>
                    <div
                        class="relative w-24 h-24 bg-white rounded-[32px] flex items-center justify-center shadow-2xl rotate-6">
                        <i class="fas fa-check text-4xl text-black"></i>
                    </div>
                </div>
                <h2 class="text-3xl font-bold tracking-tight mb-3">Otorisasi Berhasil</h2>
                <p class="text-gray-500 text-sm max-w-[200px] mx-auto leading-relaxed">Mempersiapkan lingkungan sesi
                    aman...</p>
            </div>

            <!-- Copyright -->
            <div class="mt-20 text-center md:text-left opacity-20">
                <p class="text-[9px] font-black uppercase tracking-[0.4em]">
                    &copy; 2025 Virologi Core
                </p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const csrfMeta = document.querySelector('meta[name="csrf-token"]')
        if (csrfMeta) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content')
        }

        const loginView = document.getElementById('login-view')
        const otpView = document.getElementById('otp-view')
        const successView = document.getElementById('success-view')
        const toast = document.getElementById('toast')

        const otpInputs = [
            document.getElementById('o1'),
            document.getElementById('o2'),
            document.getElementById('o3'),
            document.getElementById('o4'),
            document.getElementById('o5'),
            document.getElementById('o6'),
        ]

        function showToast(message, type = 'error') {
            if (!toast) return

            toast.className = `
            fixed top-6 right-6 z-[9999] min-w-[260px] px-5 py-4 rounded-xl
            text-sm font-semibold shadow-2xl backdrop-blur transition-all duration-300
            ${type === 'success'
                ? 'bg-emerald-500/90 text-white'
                : 'bg-red-500/90 text-white'}
        `
            toast.textContent = message
            toast.classList.remove('hidden')

            setTimeout(() => {
                toast.classList.add('opacity-0')
                setTimeout(() => {
                    toast.classList.add('hidden')
                    toast.classList.remove('opacity-0')
                }, 300)
            }, 3000)
        }

        function handleApiError(error) {
            if (error.response && error.response.data) {
                const res = error.response.data
                showToast(res.message || 'Terjadi kesalahan')
                return
            }
            showToast('Koneksi bermasalah, silakan coba lagi')
        }

        function focusNext(curr, nextId) {
            curr.value = curr.value.replace(/[^0-9]/g, '')
            if (curr.value.length === 1 && nextId) {
                document.getElementById(nextId).focus()
            }
        }

        otpInputs.forEach((box, idx) => {
            box.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !box.value && otpInputs[idx - 1]) {
                    otpInputs[idx - 1].focus()
                }
            })
            box.addEventListener('focus', () => box.value = '')
        })

        function toOTP(e) {
            e.preventDefault()

            const form = e.target
            const btn = document.getElementById('btn-login')

            btn.disabled = true
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'

            axios.post('/ai-agent/login', new FormData(form))
                .then(res => {
                    if (!res.data.success) {
                        showToast(res.data.message)
                        return Promise.reject()
                    }
                    return axios.post('/ai-agent/login/send-otp')
                })
                .then(res => {
                    if (!res.data.success) {
                        showToast(res.data.message)
                        return Promise.reject()
                    }

                    showToast(res.data.message, 'success')

                    loginView.style.opacity = '0'
                    loginView.style.transform = 'translateY(-10px)'

                    setTimeout(() => {
                        loginView.style.display = 'none'
                        otpView.style.display = 'block'
                        setTimeout(() => {
                            otpView.style.opacity = '1'
                            otpView.style.transform = 'translateY(0)'
                            otpInputs[0].focus()
                        }, 50)
                    }, 400)

                    startResendTimer()
                })
                .catch(err => {
                    if (err) handleApiError(err)
                })
                .finally(() => {
                    btn.disabled = false
                    btn.innerHTML = '<span>Lanjutkan</span><i class="fas fa-arrow-right text-[10px]"></i>'
                })
        }

        function handleVerify(e) {
            e.preventDefault()

            const btn = document.getElementById('btn-verify')
            btn.disabled = true
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'

            const otp = otpInputs.map(i => i.value).join('')

            if (otp.length !== 6) {
                showToast('Kode OTP harus terdiri dari 6 digit')
                btn.disabled = false
                btn.innerHTML = '<span>Verifikasi Akun</span>'
                return
            }

            axios.post('/ai-agent/login/verify-otp', {
                    otp
                })
                .then(res => {
                    if (!res.data.success) {
                        showToast(res.data.message)
                        return Promise.reject()
                    }

                    showToast(res.data.message, 'success')

                    otpView.style.opacity = '0'
                    otpView.style.transform = 'scale(0.98)'

                    setTimeout(() => {
                        otpView.style.display = 'none'
                        successView.style.display = 'block'
                        setTimeout(() => {
                            successView.style.opacity = '1'
                        }, 50)
                    }, 400)

                    setTimeout(() => {
                        window.location.href = '/ai-agent/chat'
                    }, 1500)
                })
                .catch(err => {
                    if (err) handleApiError(err)
                })
                .finally(() => {
                    btn.disabled = false
                    btn.innerHTML = '<span>Verifikasi Akun</span>'
                })
        }

        let resendSeconds = 45
        let resendInterval = null

        function startResendTimer() {
            const btn = document.getElementById('btn-resend')
            const timer = document.getElementById('resend-timer')

            resendSeconds = 45
            btn.disabled = true
            timer.textContent = '00:45'

            resendInterval && clearInterval(resendInterval)

            resendInterval = setInterval(() => {
                resendSeconds--
                timer.textContent = `00:${resendSeconds < 10 ? '0' + resendSeconds : resendSeconds}`

                if (resendSeconds <= 0) {
                    clearInterval(resendInterval)
                    btn.disabled = false
                    btn.textContent = 'Kirim Ulang Kode'
                }
            }, 1000)
        }

        function resendOtp() {
            const btn = document.getElementById('btn-resend')
            btn.disabled = true

            axios.post('/ai-agent/login/send-otp')
                .then(res => {
                    if (!res.data.success) {
                        showToast(res.data.message)
                        return Promise.reject()
                    }
                    showToast(res.data.message, 'success')
                    startResendTimer()
                })
                .catch(err => {
                    if (err) handleApiError(err)
                    btn.disabled = false
                })
        }
    </script>


</body>

</html>
