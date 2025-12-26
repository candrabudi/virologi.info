<?php

namespace App\Services;

use App\Models\AiLanguageAlias;
use App\Models\AiLanguageStat;
use App\Services\AgentAi\LanguageDetector;
use Illuminate\Support\Facades\DB;

class AiLanguageNormalizerService
{
    public function __construct(private LanguageDetector $detector)
    {
    }

    public function normalize(string $text, string $scope = 'cybersecurity'): array
    {
        $clean = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', mb_strtolower($text));
        $clean = trim(preg_replace('/\s+/u', ' ', $clean));

        $tokens = $clean === '' ? [] : explode(' ', $clean);
        $language = $this->detector->detect($tokens);

        $aliases = AiLanguageAlias::where([
            'scope_code' => $scope,
            'language_code' => $language,
            'is_active' => true,
        ])->get()->keyBy('raw_term');

        $normalized = [];
        $unknown = [];

        foreach ($tokens as $t) {
            if (isset($aliases[$t])) {
                $normalized[] = $aliases[$t]->normalized_term;
            } else {
                $normalized[] = $t;
                if (mb_strlen($t) >= 3) {
                    $unknown[] = $t;
                }
            }
        }

        AiLanguageStat::updateOrCreate(
            ['scope_code' => $scope, 'language_code' => $language],
            ['total_messages' => DB::raw('total_messages + 1')]
        );

        return [
            'normalized_text' => implode(' ', $normalized),
            'language_code' => $language,
            'unknown_terms' => array_values(array_unique($unknown)),
        ];
    }
}
