<?php

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use App\Models\AiSetting;
use App\Services\AgentAi\AgentAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentAiController extends Controller
{
    public function __construct(
        private readonly AgentAiService $agentAi
    ) {
    }

    public function index()
    {
        return view('agent_ai.chat');
    }

    public function sessions()
    {
        return response()->json(
            AiChatSession::query()
                ->where('user_id', auth()->id())
                ->where('is_active', true)
                ->orderByDesc('is_pinned')
                ->orderByDesc('last_activity_at')
                ->get([
                    'id',
                    'title',
                    'session_token',
                    'last_activity_at',
                    'is_pinned',
                ])
        );
    }

    public function createSession()
    {
        $setting = AiSetting::where('is_active', true)->firstOrFail();

        $session = AiChatSession::create([
            'user_id' => auth()->id(),
            'scope_code' => 'cybersecurity',
            'session_token' => (string) Str::uuid(),
            'title' => 'Percakapan Baru',
            'model' => $setting->model,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            'last_activity_at' => now(),
            'is_active' => true,
            'is_pinned' => false,
        ]);

        return response()->json([
            'session_token' => $session->session_token,
            'title' => $session->title,
        ]);
    }

    public function messages(string $token)
    {
        $session = AiChatSession::query()
            ->where('session_token', $token)
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'session' => [
                'session_token' => $session->session_token,
                'title' => $session->title,
                'model' => $session->model,
                'scope' => $session->scope_code,
                'is_pinned' => (bool) $session->is_pinned,
            ],
            'messages' => $session->messages()
                ->orderBy('id')
                ->get([
                    'id',
                    'role',
                    'content',
                    'created_at',
                    'is_liked',
                ]),
        ]);
    }

    public function storeMessage(Request $request, ?string $token = null)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        [$sessionToken, $reply, $status, $meta] = $this->agentAi->handleMessage(
            auth()->id(),
            $request->content,
            $token
        );

        return response()->json([
            'session_token' => $sessionToken,
            'content' => $reply,
            'meta' => $meta,
        ], $status);
    }

    public function togglePin(string $token)
    {
        $session = AiChatSession::query()
            ->where('session_token', $token)
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->firstOrFail();

        $session->update([
            'is_pinned' => !$session->is_pinned,
        ]);

        return response()->json([
            'success' => true,
            'is_pinned' => (bool) $session->is_pinned,
        ]);
    }

    public function deleteSession(string $token)
    {
        $session = AiChatSession::query()
            ->where('session_token', $token)
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->firstOrFail();

        $session->update([
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
