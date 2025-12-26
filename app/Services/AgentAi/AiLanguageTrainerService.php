<?php

namespace App\Services;

use App\Models\AiLanguageAlias;
use Illuminate\Support\Facades\DB;

class AiLanguageTrainerService
{
    public function learnAutomatically(
        array $unknownTerms,
        string $scope,
        string $language,
        int $confidence
    ): void {
        if ($confidence < 75) {
            return;
        }

        foreach ($unknownTerms as $term) {
            AiLanguageAlias::updateOrCreate(
                [
                    'scope_code' => $scope,
                    'language_code' => $language,
                    'raw_term' => $term,
                ],
                [
                    'normalized_term' => $term,
                    'confidence' => 1,
                    'used_count' => DB::raw('used_count + 1'),
                    'is_active' => false,
                ]
            );
        }
    }
}
