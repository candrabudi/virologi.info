<?php

namespace App\Services\AgentAi;

use App\Models\AiChatSession;
use App\Models\AiSetting;
use Illuminate\Support\Facades\Http;

class LlmClient
{
    public function chat(
        AiChatSession $session,
        AiSetting $setting,
        string $baseSystemPrompt
    ): array {
        $messages = [];

        $messages[] = [
            'role' => 'system',
            'content' => $this->buildSystemPrompt($baseSystemPrompt),
        ];

        foreach (
            $session->messages()
                ->orderByDesc('id')
                ->limit(14)
                ->get()
                ->reverse() as $msg
        ) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        $payload = [
            'model' => $setting->model,
            'messages' => $messages,
            'temperature' => (float) $setting->temperature,
            'max_tokens' => (int) $setting->max_tokens,
        ];

        $response = Http::timeout($setting->timeout)
            ->withToken($setting->api_key)
            ->post(
                $setting->base_url ?: 'https://api.openai.com/v1/chat/completions',
                $payload
            );

        if (!$response->successful()) {
            return [
                'Maaf, AI sedang tidak bisa merespons saat ini.',
                [],
            ];
        }

        $json = $response->json();

        return [
            trim($json['choices'][0]['message']['content'] ?? ''),
            [
                'prompt_tokens' => (int) ($json['usage']['prompt_tokens'] ?? 0),
                'completion_tokens' => (int) ($json['usage']['completion_tokens'] ?? 0),
                'total_tokens' => (int) ($json['usage']['total_tokens'] ?? 0),
            ],
        ];
    }

    public function classify(
        AiSetting $setting,
        string $systemPrompt
    ): array {
        return $this->rawChat($setting, $systemPrompt, 0, 4);
    }

    private function rawChat(
        AiSetting $setting,
        string $systemPrompt,
        int $temperature,
        int $maxTokens
    ): array {
        $payload = [
            'model' => $setting->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
            ],
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ];

        $response = Http::timeout($setting->timeout)
            ->withToken($setting->api_key)
            ->post(
                $setting->base_url ?: 'https://api.openai.com/v1/chat/completions',
                $payload
            );

        if (!$response->successful()) {
            return ['NO', []];
        }

        $json = $response->json();

        return [
            trim($json['choices'][0]['message']['content'] ?? ''),
            [
                'prompt_tokens' => (int) ($json['usage']['prompt_tokens'] ?? 0),
                'completion_tokens' => (int) ($json['usage']['completion_tokens'] ?? 0),
                'total_tokens' => (int) ($json['usage']['total_tokens'] ?? 0),
            ],
        ];
    }

    private function buildSystemPrompt(string $basePrompt): string
    {
        return trim($basePrompt)."\n\n".<<<PROMPT
Kamu adalah AI seperti ChatGPT yang berfokus pada Cyber Security dan Secure Software Engineering.

TUJUAN UTAMA:
- Membantu user secara natural, kontekstual, dan profesional
- Semua jawaban harus relevan dengan cyber security atau implementasi teknis (coding, server, cloud, API, infrastructure)

PEMAHAMAN KONTEKS:
- Secara default, anggap pertanyaan user berkaitan dengan percakapan sebelumnya
- Namun, JANGAN memaksakan keterkaitan jika konteks sebelumnya tidak relevan
- Jika user menggunakan referensi ambigu seperti "itu", "yang tadi", atau "yang sebelumnya":
  - Tentukan konteks yang paling masuk akal
  - Jika masih tidak jelas, minta klarifikasi singkat sebelum menjawab

PERMINTAAN TEKNIS & IMPLEMENTASI:
- Semua pertanyaan terkait kode program, konfigurasi server, cloud, API, Linux, Docker, dan security BOLEH
- Jika user meminta contoh atau implementasi:
  - Berikan solusi teknis yang konkret dan lengkap
  - Gunakan best practice dan secure-by-default
  - Fokus pada solusi, bukan teori panjang

BATASAN DOMAIN:
- Jika pertanyaan terlalu umum atau melebar:
  - Tarik jawaban ke sudut pandang cyber security atau secure engineering
  - Jangan menolak secara keras
  - Jangan menjawab di luar domain

GAYA KOMUNIKASI:
- Natural seperti ChatGPT
- Tidak kaku
- Tidak menggurui
- Adaptif terhadap konteks percakapan
- Fokus membantu user menyelesaikan masalah
PROMPT;
    }
}
