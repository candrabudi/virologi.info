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
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'status' => 'active',
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
            ]);

            DB::commit();

            return $this->ok([
                'redirect' => '/ai-agent/login',
            ], 'Registrasi berhasil');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->fail('Registrasi gagal', null, 500);
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
                'message' => 'Terlalu banyak percobaan login, silakan tunggu sebentar',
            ], 429);
        }
        RateLimiter::hit($ipKey, 60);

        $identity = Str::lower(trim($request->identity));

        $user = User::where(function ($q) use ($identity) {
            $q->whereRaw('LOWER(username) = ?', [$identity])
              ->orWhereRaw('LOWER(email) = ?', [$identity]);
        })
            // ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username, email, atau kata sandi tidak valid',
            ], 401);
        }

        session([
            'step1_user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kredensial valid',
        ]);
    }

    public function sendOtp(Request $request)
    {
        $userId = session('step1_user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login telah berakhir, silakan login ulang',
            ], 419);
        }

        $user = User::where('id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        $otpKey = 'otp-send-user:'.$user->id;
        if (RateLimiter::tooManyAttempts($otpKey, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu sering meminta OTP, silakan tunggu',
            ], 429);
        }
        RateLimiter::hit($otpKey, 60);

        $otp = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

        Otp::where('user_id', $user->id)
            ->where('purpose', 'login')
            ->whereNull('verified_at')
            ->update(['expires_at' => Carbon::now()]);

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
            "Kode OTP Login Panel Virologi:\n\n".
            "{$otp}\n\n".
            "Berlaku sampai {$expiresAt->format('Y-m-d H:i:s')} WIB\n\n".
            'Jika ini bukan Anda, abaikan email ini.';

        Mail::raw($body, function ($message) use ($user) {
            $message->to($user->email)->subject('Kode OTP Login Panel Virologi');
        });

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda',
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
                'message' => 'Sesi login telah berakhir, silakan login ulang',
            ], 419);
        }

        $verifyKey = 'otp-verify-user:'.$userId;
        if (RateLimiter::tooManyAttempts($verifyKey, 8)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan OTP, silakan tunggu',
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
                'message' => 'Kode OTP salah atau sudah kedaluwarsa',
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
            'message' => 'Login berhasil',
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
