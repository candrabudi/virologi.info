<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiContext;
use App\Models\AiPromptBinding;
use App\Models\AiPromptTemplate;
use App\Models\AiRule;
use App\Models\AiSetting;
use App\Models\AiUsageLog;
use App\Models\Ebook;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AgentAiController extends Controller
{
    public function index()
    {
        return view('agent_ai.chat');
    }

    public function sessions()
    {
        return response()->json(
            AiChatSession::where('user_id', auth()->id())
                ->where('is_active', true)
                ->orderByDesc('last_activity_at')
                ->get(['id', 'title', 'session_token', 'last_activity_at'])
        );
    }

    public function createSession()
    {
        $session = AiChatSession::create(['user_id' => auth()->id(), 'session_token' => Str::uuid()->toString(), 'title' => 'Percakapan Baru', 'model' => 'Virologi-o1', 'ip_address' => request()->ip(), 'user_agent' => substr(request()->userAgent(), 0, 255), 'last_activity_at' => now()]);

        return response()->json(['session_token' => $session->session_token, 'title' => $session->title]);
    }

    public function messages(string $token)
    {
        $session = AiChatSession::where('session_token', $token)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'session' => [
                'session_token' => $session->session_token,
                'title' => $session->title,
                'model' => $session->model,
            ],
            'messages' => $session->messages()
                ->orderBy('id')
                ->get(['role', 'content', 'created_at']),
        ]);
    }

    public function storeMessage(Request $request, ?string $token = null)
    {
        $request->validate(['content' => 'required|string']);

        $setting = $this->activeSetting();
        $context = $this->resolveContext($request->content);

        $session = $this->resolveSession($token, $setting);

        $blocked = $this->checkRules($request->content, $context);
        if ($blocked) {
            $this->logUsage(true, $blocked, $setting, $context);

            return response()->json([
                'session_token' => $session->session_token,
                'content' => 'Permintaan ditolak oleh kebijakan keamanan AI.',
            ], 403);
        }

        AiChatMessage::create([
            'session_id' => $session->id,
            'role' => 'user',
            'content' => $request->content,
        ]);

        $topicChanged = $this->isTopicChanged($session, $context);

        $ebooks = $this->resolveEbooks($request->content, $context);

        $resourceType = $this->detectResourceType($request->content);
        $resources = $this->resolveResources($request->content, $resourceType);

        if (in_array($resourceType, ['ebook', 'product', 'service'], true) && $resources->isEmpty()) {
            $content = $this->buildEmptyResourceResponse($resourceType);

            AiChatMessage::create([
                'session_id' => $session->id,
                'role' => 'assistant',
                'content' => $content,
            ]);

            return response()->json([
                'session_token' => $session->session_token,
                'content' => $content,
            ]);
        }

        [$assistant, $usage] = $this->generateAiResponse(
            $session,
            $setting,
            $context,
            $topicChanged,
            $resources,
            $resourceType
        );

        AiChatMessage::create([
            'session_id' => $session->id,
            'role' => 'assistant',
            'content' => $assistant,
        ]);

        if ($session->title === 'Percakapan Baru') {
            $session->update([
                'title' => $this->generateTitle($session, $setting),
            ]);
        }

        $session->update(['last_activity_at' => now()]);
        $this->logUsage(false, null, $setting, $context, $usage);

        return response()->json([
            'session_token' => $session->session_token,
            'content' => $assistant,
            'meta' => [
                'context' => $context->code,
                'topic_changed' => $topicChanged,
                'ebooks_count' => $ebooks ? $ebooks->count() : 0,
            ],
        ]);
    }

    protected function resolveSession(?string $token, AiSetting $setting): AiChatSession
    {
        if ($token) {
            $session = AiChatSession::where('session_token', $token)
                ->where('user_id', auth()->id())
                ->first();
            if ($session) {
                return $session;
            }
        }

        return AiChatSession::create([
            'user_id' => auth()->id(),
            'session_token' => Str::uuid()->toString(),
            'title' => 'Percakapan Baru',
            'model' => $setting->model,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent(), 0, 255),
            'last_activity_at' => now(),
        ]);
    }

    protected function activeSetting(): AiSetting
    {
        return AiSetting::where('is_active', true)->firstOrFail();
    }

    protected function resolveContext(string $prompt): AiContext
    {
        $p = mb_strtolower($prompt);

        if (preg_match('/\b(ebook|e-book|buku|modul|materi|pdf)\b/u', $p)) {
            return AiContext::where('code', 'product')->firstOrFail();
        }

        if (preg_match('/\b(belajar|kursus|roadmap|pemula|materi belajar)\b/u', $p)) {
            return AiContext::where('code', 'learning')->firstOrFail();
        }

        if (preg_match('/\b(konsultasi|audit|jasa|layanan|training perusahaan)\b/u', $p)) {
            return AiContext::where('code', 'service')->firstOrFail();
        }

        return AiContext::where('code', 'general')->firstOrFail();
    }

    protected function isTopicChanged(AiChatSession $session, AiContext $newContext): bool
    {
        $lastUser = $session->messages()
            ->where('role', 'user')
            ->orderByDesc('id')
            ->skip(1)
            ->first();

        if (!$lastUser) {
            return false;
        }

        $prevContext = $this->resolveContext($lastUser->content);

        return $prevContext->code !== $newContext->code;
    }

    protected function checkRules(string $prompt, AiContext $context): ?string
    {
        $rules = AiRule::where('is_active', true)
            ->where(function ($q) use ($context) {
                $q->whereNull('ai_context_id')
                  ->orWhere('ai_context_id', $context->id);
            })
            ->get();

        $p = mb_strtolower($prompt);

        foreach ($rules as $rule) {
            if ($rule->type === 'keyword' && str_contains($p, mb_strtolower($rule->value))) {
                return 'Blocked keyword: '.$rule->value;
            }

            if ($rule->type === 'regex') {
                $pattern = '/'.str_replace('/', '\/', $rule->value).'/iu';
                if (@preg_match($pattern, $prompt)) {
                    if (preg_match($pattern, $prompt)) {
                        return 'Blocked pattern';
                    }
                }
            }
        }

        return null;
    }

    protected function resolveEbooks(string $prompt, AiContext $context)
    {
        if (!in_array($context->code, ['product', 'learning'], true)) {
            return collect();
        }

        $level = $this->extractLevel($prompt);
        $topic = $this->extractTopic($prompt);

        $q = Ebook::where('is_active', true);

        if ($level) {
            $q->where('level', $level);
        }

        if ($topic) {
            $q->where('topic', $topic);
        }

        $tokens = $this->tokenize($prompt);

        $q->where(function ($qq) use ($prompt, $tokens) {
            $qq->where('title', 'like', '%'.$prompt.'%')
                ->orWhere('summary', 'like', '%'.$prompt.'%');

            foreach ($tokens as $t) {
                $qq->orWhere('title', 'like', '%'.$t.'%')
                    ->orWhere('summary', 'like', '%'.$t.'%')
                    ->orWhere('ai_keywords', 'like', '%'.$t.'%');
            }
        });

        return $q->orderBy('sort_order')
            ->limit(6)
            ->get(['title', 'summary', 'level', 'topic', 'slug']);
    }

    protected function tokenize(string $text): array
    {
        $t = mb_strtolower($text);
        $t = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $t);
        $parts = preg_split('/\s+/u', trim($t)) ?: [];
        $stop = [
            'yang', 'dan', 'atau', 'untuk', 'dari', 'ke', 'di', 'ini', 'itu', 'saya', 'aku', 'kamu', 'anda',
            'mau', 'ingin', 'butuh', 'tolong', 'dong', 'nih', 'ya', 'ga', 'gak', 'nggak', 'aja', 'banget',
            'tentang', 'mengenai', 'apa', 'gimana', 'bagaimana', 'bisa', 'dapat', 'rekomendasi', 'carikan',
            'ebook', 'buku', 'modul', 'materi', 'pdf', 'belajar',
        ];

        $out = [];
        foreach ($parts as $p) {
            if (mb_strlen($p) < 3) {
                continue;
            }
            if (in_array($p, $stop, true)) {
                continue;
            }
            $out[] = $p;
        }

        return array_values(array_unique(array_slice($out, 0, 10)));
    }

    protected function extractLevel(string $prompt): ?string
    {
        $p = mb_strtolower($prompt);

        if (preg_match('/\b(pemula|beginner|dasar)\b/u', $p)) {
            return 'beginner';
        }
        if (preg_match('/\b(menengah|intermediate)\b/u', $p)) {
            return 'intermediate';
        }
        if (preg_match('/\b(lanjutan|advanced|mahir)\b/u', $p)) {
            return 'advanced';
        }

        return null;
    }

    protected function extractTopic(string $prompt): ?string
    {
        $p = mb_strtolower($prompt);

        $map = [
            'network_security' => ['network', 'jaringan', 'firewall', 'ids', 'ips', 'routing', 'switching'],
            'application_security' => ['web', 'aplikasi', 'owasp', 'xss', 'sql injection', 'sqli', 'csrf'],
            'cloud_security' => ['cloud', 'aws', 'azure', 'gcp', 'kubernetes', 'k8s', 'container'],
            'soc' => ['soc', 'siem', 'blue team', 'log', 'splunk', 'sentinel'],
            'pentest' => ['pentest', 'penetration', 'ethical hacking', 'red team', 'burp', 'nmap'],
            'malware' => ['malware', 'ransomware', 'trojan', 'reverse', 'analysis'],
            'incident_response' => ['incident', 'ir', 'forensic', 'forensik', 'breach', 'response'],
            'governance' => ['governance', 'grc', 'iso 27001', 'risk', 'compliance', 'policy'],
        ];

        foreach ($map as $topic => $words) {
            foreach ($words as $w) {
                if (str_contains($p, $w)) {
                    return $topic;
                }
            }
        }

        return null;
    }

    protected function detectResourceType(string $prompt): string
    {
        $p = mb_strtolower($prompt);

        if (preg_match('/\b(ebook|e-book|buku|pdf|modul)\b/u', $p)) {
            return 'ebook';
        }

        if (preg_match('/\b(layanan|jasa|konsultasi|audit|training)\b/u', $p)) {
            return 'service';
        }

        if (preg_match('/\b(produk|hardware|device|appliance|firewall|tool)\b/u', $p)) {
            return 'product';
        }

        return 'unknown';
    }

    protected function resolveResources(string $prompt, string $type)
    {
        $keywords = $this->extractKeywords($prompt);

        if (empty($keywords)) {
            return collect();
        }

        return match ($type) {
            'ebook' => Ebook::where('is_active', true)
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('title', 'like', "%{$word}%")
                          ->orWhere('ai_keywords', 'like', "%{$word}%");
                    }
                })
                ->limit(5)
                ->get(),

            'product' => Product::where('is_active', true)
                ->where('is_ai_visible', true)
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('ai_keywords', 'like', "%{$word}%");
                    }
                })
                ->limit(5)
                ->get(),

            'service' => CyberSecurityService::where('is_active', true)
                ->where('is_ai_visible', true)
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('ai_keywords', 'like', "%{$word}%")
                          ->orWhere('summary', 'like', "%{$word}%");
                    }
                })
                ->limit(5)
                ->get(),

            default => collect(),
        };
    }

    protected function extractKeywords(string $prompt): array
    {
        $prompt = mb_strtolower($prompt);

        $prompt = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $prompt);
        $words = preg_split('/\s+/u', $prompt, -1, PREG_SPLIT_NO_EMPTY);

        $stopwords = [
            'dan', 'atau', 'yang', 'dengan', 'untuk', 'ada', 'apakah',
            'saya', 'aku', 'ingin', 'butuh', 'cari', 'tentang',
        ];

        return collect($words)
            ->filter(fn ($w) => mb_strlen($w) >= 3)
            ->reject(fn ($w) => in_array($w, $stopwords, true))
            ->unique()
            ->values()
            ->all();
    }

    protected function buildSystemPrompt(
        AiContext $context,
        bool $topicChanged,
        $resources,
        string $resourceType
    ): string {
        $templates = AiPromptTemplate::where('is_active', true)
            ->whereIn(
                'id',
                AiPromptBinding::where('ai_context_id', $context->id)
                    ->where('is_active', true)
                    ->pluck('ai_prompt_template_id')
            )
            ->pluck('content')
            ->toArray();

        $prompt = implode("\n\n", $templates);

        if ($topicChanged) {
            $prompt .= "\n\nTOPIC SWITCH:\n";
            $prompt .= "- Abaikan seluruh topik sebelumnya.\n";
            $prompt .= "- Fokus hanya pada permintaan terbaru.\n";
        }

        $prompt .= "\n\nATURAN KERAS RESOURCE:\n";
        $prompt .= "- Resource yang diminta: {$resourceType}\n";
        $prompt .= "- Jangan membahas resource lain yang tidak diminta.\n";
        $prompt .= "- Jangan mengarang data yang tidak ada di sistem.\n";

        if ($resourceType === 'ebook') {
            $prompt .= "- Jika ebook tersedia, WAJIB sertakan link internal.\n";
            $prompt .= "- Format link: {$this->ebookBaseUrl()}/{slug}\n";
            $prompt .= "- Jangan memberikan link eksternal jika ebook internal tersedia.\n";
        }

        if (in_array($resourceType, ['product', 'service'], true)) {
            $prompt .= "- Jika {$resourceType} tersedia, WAJIB sertakan CTA URL.\n";
            $prompt .= "- Gunakan field cta_label dan cta_url.\n";
        }

        if ($resources && $resources->count()) {
            $prompt .= "\n\nDATA {$resourceType} TERSEDIA (INTERNAL):\n";

            foreach ($resources as $r) {
                if ($resourceType === 'ebook') {
                    $prompt .= "- Judul: {$r->title}\n";
                    $prompt .= "  Level: {$r->level}\n";
                    $prompt .= "  Topik: {$r->topic}\n";
                    $prompt .= "  Ringkasan: {$r->summary}\n";
                    $prompt .= "  Link: {$this->ebookBaseUrl()}/{$r->slug}\n";
                }

                if (in_array($resourceType, ['product', 'service'], true)) {
                    $prompt .= "- Nama: {$r->name}\n";
                    $prompt .= "  Tipe: {$r->product_type}\n";
                    $prompt .= "  Domain: {$r->ai_domain}\n";
                    $prompt .= "  Ringkasan: {$r->summary}\n";
                    $prompt .= "  CTA: {$r->cta_label} ({$r->cta_url})\n";
                }
            }

            $prompt .= "\nGunakan HANYA data internal di atas.\n";
        } else {
            $prompt .= "\n\nDATA {$resourceType} TIDAK TERSEDIA:\n";
            $prompt .= "- Sampaikan dengan jujur bahwa {$resourceType} belum tersedia.\n";
            $prompt .= "- Jangan mengarang judul, vendor, atau link.\n";
            $prompt .= "- Jangan mengalihkan ke topik lain tanpa diminta.\n";

            if ($resourceType === 'ebook') {
                $prompt .= "- Tanyakan topik dan level yang diinginkan pengguna.\n";
            }
        }

        return $prompt;
    }

    protected function buildEmptyResourceResponse(string $type): string
    {
        return match ($type) {
            'ebook' => "Saat ini kami belum memiliki ebook yang sesuai dengan topik tersebut.\n"
                .'Anda dapat mencoba topik lain seperti SOC, Network Security, atau Pentesting.',

            'product' => "Saat ini kami belum memiliki produk yang sesuai dengan kebutuhan tersebut.\n"
                .'Silakan hubungi kami jika ingin rekomendasi solusi alternatif.',

            'service' => "Saat ini layanan tersebut belum tersedia.\n"
                .'Anda dapat menanyakan layanan keamanan lain seperti audit, pentest, atau training.',

            default => "Saat ini kami belum memiliki informasi atau resource yang sesuai dengan permintaan Anda.\n"
                .'Silakan perjelas apakah Anda mencari ebook, produk, atau layanan keamanan siber.',
        };
    }

    protected function ebookBaseUrl(): string
    {
        return url('/ebooks');
    }

    protected function buildMessagesForAi(AiChatSession $session, AiContext $context, bool $topicChanged): array
    {
        if ($topicChanged) {
            $lastUser = $session->messages()->where('role', 'user')->orderByDesc('id')->first();

            return [
                ['role' => 'user', 'content' => $lastUser?->content ?? ''],
            ];
        }

        if ($context->code === 'product') {
            return $session->messages()
                ->orderByDesc('id')
                ->limit(8)
                ->get(['role', 'content'])
                ->reverse()
                ->values()
                ->toArray();
        }

        return $session->messages()
            ->orderByDesc('id')
            ->limit(15)
            ->get(['role', 'content'])
            ->reverse()
            ->values()
            ->toArray();
    }

    protected function generateAiResponse(
        AiChatSession $session,
        AiSetting $setting,
        AiContext $context,
        bool $topicChanged,
        $resources,
        string $resourceType
    ): array {
        $systemPrompt = $this->buildSystemPrompt(
            $context,
            $topicChanged,
            $resources,
            $resourceType
        ) ?: 'Anda adalah AI cybersecurity assistant profesional.';

        $messages = $this->buildMessagesForAi($session, $context, $topicChanged);

        $payload = [
            'model' => $setting->model,
            'temperature' => (float) $setting->temperature,
            'max_tokens' => (int) $setting->max_tokens,
            'messages' => array_merge(
                [['role' => 'system', 'content' => $systemPrompt]],
                $messages
            ),
        ];

        $response = Http::timeout($setting->timeout)
            ->withToken($setting->api_key)
            ->post($setting->base_url, $payload)
            ->json();

        $content = data_get($response, 'choices.0.message.content');

        return [
            $content ?: 'Gagal menghasilkan respons.',
            data_get($response, 'usage', []),
        ];
    }

    protected function logUsage(
        bool $blocked,
        ?string $reason,
        AiSetting $setting,
        AiContext $context,
        array $usage = []
    ): void {
        AiUsageLog::create([
            'provider' => $setting->provider,
            'model' => $setting->model,
            'category' => $context->category,
            'prompt_tokens' => (int) ($usage['prompt_tokens'] ?? 0),
            'completion_tokens' => (int) ($usage['completion_tokens'] ?? 0),
            'total_tokens' => (int) ($usage['total_tokens'] ?? 0),
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent(), 0, 255),
            'is_blocked' => $blocked,
            'block_reason' => $reason,
        ]);
    }

    protected function generateTitle(AiChatSession $session, AiSetting $setting): string
    {
        $first = $session->messages()->where('role', 'user')->orderBy('id')->first();
        if (!$first) {
            return 'Percakapan Baru';
        }

        $response = Http::timeout($setting->timeout)
            ->withToken($setting->api_key)
            ->post($setting->base_url, [
                'model' => $setting->model,
                'temperature' => 0.3,
                'max_tokens' => 40,
                'messages' => [
                    ['role' => 'system', 'content' => 'Buat judul singkat maksimal 6 kata, profesional, tanpa emoji dan tanpa tanda kutip.'],
                    ['role' => 'user', 'content' => $first->content],
                ],
            ])
            ->json();

        return Str::limit(trim(data_get($response, 'choices.0.message.content', 'Percakapan Baru')), 60);
    }

    public function togglePin($token)
    {
        $session = AiChatSession::where('session_token', $token)
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->firstOrFail();

        $session->update([
            'is_pinned' => !$session->is_pinned,
        ]);

        return response()->json([
            'success' => true,
            'is_pinned' => $session->is_pinned,
        ]);
    }

    public function deleteSession($token)
    {
        $session = AiChatSession::where('session_token', $token)
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
