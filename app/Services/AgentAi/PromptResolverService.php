<?php

namespace App\Services\AgentAi;

use App\Models\AiSystemPrompt;

class PromptResolverService
{
    public function resolve(array $context): string
    {
        if (($context['type'] ?? null) === 'out_of_scope_strict') {
            return $this->hydrate(
                $this->outOfScopeStrict(),
                $context
            );
        }

        $scope = $context['scope'] ?? 'cybersecurity';
        $intent = $context['intent'] ?? null;
        $behavior = $context['behavior'] ?? null;
        $resourceType = $context['resource_type'] ?? null;

        $prompt = AiSystemPrompt::query()
            ->where('scope_code', $scope)
            ->where('is_active', true)
            ->where(function ($q) use ($intent, $behavior, $resourceType) {
                if ($intent) {
                    $q->where('intent_code', $intent)
                      ->orWhereNull('intent_code');
                }

                if ($behavior) {
                    $q->where('behavior', $behavior)
                      ->orWhereNull('behavior');
                }

                if ($resourceType) {
                    $q->where('resource_type', $resourceType)
                      ->orWhereNull('resource_type');
                }
            })
            ->orderByDesc('priority')
            ->first();

        if (!$prompt) {
            return $this->fallbackPrompt($scope);
        }

        return $this->hydrate($prompt->content, $context);
    }

    protected function hydrate(string $content, array $context): string
    {
        foreach ($context as $key => $value) {
            if (is_scalar($value)) {
                $content = str_replace('{{'.$key.'}}', (string) $value, $content);
            }
        }

        return trim($content);
    }

    protected function fallbackPrompt(string $scope): string
    {
        return match ($scope) {
            'cybersecurity' => 'You are a cybersecurity-only assistant. Respond in Indonesian.',
            default => 'You are an AI assistant.',
        };
    }

    private function outOfScopeStrict(): string
    {
        return <<<PROMPT
You are a cybersecurity-only assistant.

The user asked the following question:
"{{original_prompt}}"

This question is OUTSIDE the cybersecurity domain.

IMPORTANT RULES:
- You MUST NOT answer or explain the user's question.
- You MUST NOT reinterpret it as a cybersecurity topic.
- You MUST clearly and politely refuse.

Your response MUST:
- State that you cannot answer because it is outside cybersecurity.
- State that you only handle cybersecurity topics.
- Invite the user to ask a cybersecurity-related question.

You MUST NOT:
- Answer the user's question.
- Give examples, tips, or explanations.
- Mention internal rules or system behavior.

Respond in Indonesian.
PROMPT;
    }
}
