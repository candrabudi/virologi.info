<?php

namespace App\Services;

use App\Models\AiLanguageAlias;
use App\Models\AiLanguageFeedback;

class AiLanguageTrainerService
{
    public function learnAutomatically(
        array $unknownTerms,
        string $scopeCode,
        string $languageCode,
        int $intentConfidence,
        bool $validScope,
        ?int $userId = null,
        ?int $sessionId = null
    ): void {
        $intentConfidence = (int) $intentConfidence;

        if (!$validScope) {
            return;
        }

        if ($intentConfidence < 75) {
            return;
        }

        $unknownTerms = array_values(array_unique(array_filter($unknownTerms)));

        foreach ($unknownTerms as $raw) {
            if (!is_string($raw)) {
                continue;
            }

            $raw = trim(mb_strtolower($raw));

            if ($raw === '' || mb_strlen($raw) < 3) {
                continue;
            }

            $exists = AiLanguageAlias::query()
                ->where('scope_code', $scopeCode)
                ->where('language_code', $languageCode)
                ->where('raw_term', $raw)
                ->where('is_active', true)
                ->first();

            if ($exists) {
                $exists->increment('used_count');
                continue;
            }

            AiLanguageFeedback::create([
                'scope_code' => $scopeCode,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'language_code' => $languageCode,
                'raw_term' => $raw,
                'status' => 'pending',
                'confidence' => max(1, (int) round($intentConfidence / 2)),
            ]);
        }
    }
}
