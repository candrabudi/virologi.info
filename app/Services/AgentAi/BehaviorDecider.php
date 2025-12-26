<?php

namespace App\Services\AgentAi;

use App\Models\AiBehaviorRule;

class BehaviorDecider
{
    public function decide(string $scope, string $prompt, string $intent, int $confidence, int $unknown): array
    {
        foreach (AiBehaviorRule::where('scope_code', $scope)->orderByDesc('priority')->get() as $r) {
            if ($r->intent_code && $r->intent_code !== $intent) {
                continue;
            }
            if ($confidence < $r->min_intent_confidence) {
                continue;
            }
            if ($unknown > $r->max_unknown_terms) {
                continue;
            }
            if (str_contains($prompt, $r->pattern_value)) {
                return ['decision' => $r->decision, 'guided_kind' => $r->guided_kind];
            }
        }

        return ['decision' => 'direct', 'guided_kind' => 'none'];
    }
}
