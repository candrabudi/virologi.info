<?php

namespace App\Services\AgentAi;

use App\Models\AssistantIntent;
use App\Models\AssistantIntentTerm;

class IntentResolver
{
    public function resolve(string $prompt): object
    {
        $scores = [];

        foreach (AssistantIntentTerm::where('is_active', true)->get() as $t) {
            if (str_contains($prompt, $t->term)) {
                $scores[$t->assistant_intent_id] = ($scores[$t->assistant_intent_id] ?? 0) + $t->weight;
            }
        }

        if (!$scores) {
            return (object) ['code' => 'general', 'confidence' => 0];
        }

        arsort($scores);
        $intent = AssistantIntent::find(array_key_first($scores));

        return (object) [
            'code' => $intent->code,
            'confidence' => min(100, current($scores) * 2),
        ];
    }
}
