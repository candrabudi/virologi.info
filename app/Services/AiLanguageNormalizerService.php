<?php

namespace App\Services;

use App\Models\AiLanguageAlias;

class AiLanguageNormalizerService
{
    public function normalize(string $text): array
    {
        $text = strtolower(trim($text));
        $words = preg_split('/\s+/', $text);

        $aliases = AiLanguageAlias::where('is_active', true)->get()->keyBy('raw_term');

        $normalizedWords = [];
        $unknownWords = [];

        foreach ($words as $word) {
            if (isset($aliases[$word])) {
                $normalizedWords[] = $aliases[$word]->normalized_term;
            } else {
                $normalizedWords[] = $word;
                $unknownWords[] = $word;
            }
        }

        return [
            'original_text' => $text,
            'normalized_text' => implode(' ', $normalizedWords),
            'unknown_terms' => array_values(array_unique($unknownWords)),
        ];
    }
}
