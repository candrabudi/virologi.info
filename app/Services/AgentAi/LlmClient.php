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
        string $systemPrompt
    ): array {
        $messages = [];

        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt,
        ];

        foreach ($session->messages()->orderByDesc('id')->limit(10)->get()->reverse() as $msg) {
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

    public function classify(AiSetting $setting, string $systemPrompt): array
    {
        return $this->rawChat($setting, $systemPrompt, 0, 3);
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
}
