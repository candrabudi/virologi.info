<?php

namespace App\Services\AgentAi;

use App\Models\AiChatSession;
use App\Models\AiSetting;

class TitleGenerator
{
    public function __construct(
        private readonly LlmClient $llm
    ) {
    }

    public function generateFromUserPrompt(
        AiChatSession $session,
        AiSetting $setting,
        string $userPrompt,
        string $language = 'id'
    ): string {
        $systemPrompt = $this->systemPrompt($language);

        [$result] = $this->llm->chat(
            $session,
            $setting,
            $systemPrompt,
            [
                [
                    'role' => 'user',
                    'content' => $userPrompt,
                ],
            ]
        );

        return trim(preg_replace('/[\r\n]+/', ' ', (string) $result));
    }

    private function systemPrompt(string $lang): string
    {
        return match ($lang) {
            'en' => 'Summarize the user message into a short chat title (3-7 words). Do not answer.',
            default => 'Ringkas pesan user menjadi judul percakapan 3–7 kata. Jangan menjawab.',
        };
    }
}
