<?php

namespace App\Services\AgentAi;

use App\Services\AiLanguageNormalizerService;

class PromptNormalizer
{
    public function __construct(private AiLanguageNormalizerService $normalizer)
    {
    }

    public function normalizeWithMeta(string $raw): array
    {
        return $this->normalizer->normalize($raw);
    }
}
