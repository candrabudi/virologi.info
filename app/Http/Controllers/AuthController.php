<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    private function fail(string $message = 'Request failed', $errors = null, int $code = 400)
    {
        $payload = [
            'status' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    public function showLogin()
    {
        return view('agent_ai.auth.login');
    }

    public function showRegister()
    {
        return view('agent_ai.auth.register');
    }

    public function register(Request $request)
    {
        $key = 'register:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return $this->fail(
                'Terlalu banyak percobaan. Silakan tunggu beberapa saat.',
                null,
                429
            );
        }

        RateLimiter::hit($key, 60);

        $input = [
            'full_name' => trim(strip_tags($request->full_name)),
            'username' => trim(strtolower($request->username)),
            'phone_number' => trim($request->phone_number),
            'email' => trim(strtolower($request->email)),
            'password' => $request->password,
        ];

        $validator = Validator::make(
            $input,
            [
                'full_name' => 'required|string|min:3|max:150',
                'username' => [
                    'required',
                    'string',
                    'min:4',
                    'max:50',
                    'regex:/^[a-z0-9_]+$/',
                    'unique:users,username',
                ],
                'phone_number' => [
                    'string',
                    'max:20',
                    'regex:/^08[0-9]{8,12}$/',
                ],
                'email' => 'required|email:rfc,dns|max:150|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                ],
            ],
            [
                'full_name.required' => 'Nama lengkap wajib diisi.',
                'full_name.min' => 'Nama lengkap minimal 3 karakter.',
                'full_name.max' => 'Nama lengkap maksimal 150 karakter.',

                'username.required' => 'Username wajib diisi.',
                'username.min' => 'Username minimal 4 karakter.',
                'username.max' => 'Username maksimal 50 karakter.',
                'username.regex' => 'Username hanya boleh huruf kecil, angka, dan underscore.',
                'username.unique' => 'Username sudah digunakan.',

                'phone_number.required' => 'Nomor WhatsApp wajib diisi.',
                'phone_number.regex' => 'Format nomor WhatsApp tidak valid (contoh: 08xxxxxxxxxx).',

                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Alamat email sudah terdaftar.',

                'password.required' => 'Kata sandi wajib diisi.',
                'password.min' => 'Kata sandi minimal 8 karakter.',
                'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, dan angka.',
            ]
        );

        if ($validator->fails()) {
            return $this->fail(
                'Validasi gagal, periksa kembali data yang Anda masukkan.',
                $validator->errors(),
                422
            );
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $input['username'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'role' => 'user',
                'status' => 'active',
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                'full_name' => $input['full_name'],
                'phone_number' => $input['phone_number'],
            ]);

            DB::commit();

            RateLimiter::clear($key);

            return $this->ok([
                'redirect' => '/ai-agent/login',
            ], 'Registrasi berhasil. Silakan login.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->fail(
                'Terjadi kesalahan pada sistem. Silakan coba kembali.',
                null,
                500
            );
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        $ipKey = 'login-ip:'.$request->ip();

        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return response()->json([
                'success' => false,
                'code' => 'LOGIN_RATE_LIMIT',
                'message' => 'Terlalu banyak percobaan login dari perangkat ini. Silakan coba lagi dalam 1 menit.',
            ], 429);
        }

        RateLimiter::hit($ipKey, 60);

        $identity = Str::lower(trim($request->identity));

        $user = User::where(function ($q) use ($identity) {
            $q->whereRaw('LOWER(username) = ?', [$identity])
              ->orWhereRaw('LOWER(email) = ?', [$identity]);
        })
            ->where('status', 'active')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'code' => 'INVALID_CREDENTIALS',
                'message' => 'Login gagal. Username/email atau kata sandi salah.',
            ], 401);
        }

        session([
            'step1_user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'code' => 'LOGIN_STEP1_SUCCESS',
            'message' => 'Login tahap pertama berhasil. Silakan lanjutkan verifikasi OTP.',
        ]);
    }

    public function sendOtp(Request $request)
    {
        $userId = session('step1_user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'code' => 'SESSION_EXPIRED',
                'message' => 'Sesi login telah berakhir. Silakan login ulang untuk melanjutkan.',
            ], 419);
        }

        $user = User::where('id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'code' => 'ACCESS_DENIED',
                'message' => 'Akses ditolak. Akun tidak valid atau sudah tidak aktif.',
            ], 403);
        }

        $otpKey = 'otp-send-user:'.$user->id;

        if (RateLimiter::tooManyAttempts($otpKey, 5)) {
            return response()->json([
                'success' => false,
                'code' => 'OTP_SEND_RATE_LIMIT',
                'message' => 'Anda terlalu sering meminta kode OTP. Silakan tunggu beberapa saat sebelum mencoba lagi.',
            ], 429);
        }

        RateLimiter::hit($otpKey, 60);

        $otp = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

        Otp::where('user_id', $user->id)
            ->where('purpose', 'login')
            ->whereNull('verified_at')
            ->update([
                'expires_at' => Carbon::now(),
            ]);

        Otp::create([
            'user_id' => $user->id,
            'code_hash' => password_hash($otp, PASSWORD_BCRYPT),
            'code_last4' => substr($otp, -4),
            'purpose' => 'login',
            'expires_at' => $expiresAt,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit($request->userAgent(), 255),
        ]);

        $body =
            "Halo {$user->username}\n\n".
            "Berikut adalah kode OTP untuk login ke Panel Virologi:\n\n".
            "{$otp}\n\n".
            "Kode ini berlaku hingga {$expiresAt->format('d M Y H:i:s')} WIB.\n\n".
            'Jika Anda tidak merasa melakukan login, abaikan email ini.';

        Mail::raw($body, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Login - Panel Virologi');
        });

        return response()->json([
            'success' => true,
            'code' => 'OTP_SENT',
            'message' => 'Kode OTP telah dikirim ke email Anda. Kode berlaku selama 5 menit.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = session('step1_user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'code' => 'SESSION_EXPIRED',
                'message' => 'Sesi login telah berakhir. Silakan login ulang untuk mendapatkan OTP baru.',
            ], 419);
        }

        $verifyKey = 'otp-verify-user:'.$userId;

        if (RateLimiter::tooManyAttempts($verifyKey, 8)) {
            return response()->json([
                'success' => false,
                'code' => 'OTP_VERIFY_RATE_LIMIT',
                'message' => 'Terlalu banyak percobaan OTP. Silakan tunggu sebentar sebelum mencoba kembali.',
            ], 429);
        }

        RateLimiter::hit($verifyKey, 60);

        $otp = Otp::where('user_id', $userId)
            ->where('purpose', 'login')
            ->whereNull('verified_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otp || !password_verify($request->otp, $otp->code_hash)) {
            return response()->json([
                'success' => false,
                'code' => 'OTP_INVALID',
                'message' => 'Kode OTP tidak valid atau sudah kedaluwarsa. Silakan minta OTP baru.',
            ], 401);
        }

        $otp->update([
            'verified_at' => Carbon::now(),
        ]);

        $user = User::where('id', $userId)
            ->where('status', 'active')
            ->firstOrFail();

        Auth::login($user, true);
        $request->session()->regenerate();

        $user->update([
            'last_login_at' => Carbon::now(),
        ]);

        session()->forget('step1_user_id');
        RateLimiter::clear($verifyKey);

        return response()->json([
            'success' => true,
            'code' => 'LOGIN_SUCCESS',
            'message' => 'Login berhasil. Selamat datang di panel.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout',
        ]);
    }
}
