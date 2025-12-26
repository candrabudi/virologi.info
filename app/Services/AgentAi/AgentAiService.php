<?php

namespace App\Services\AgentAi;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiSetting;
use App\Models\AiUsageLog;
use App\Services\AiLanguageTrainerService;

class AgentAiService
{
    public function __construct(
        private readonly PromptNormalizer $normalizer,
        private readonly ScopeGate $scopeGate,
        private readonly IntentResolver $intentResolver,
        private readonly BehaviorDecider $behaviorDecider,
        private readonly ResourceResolver $resourceResolver,
        private readonly PromptResolverService $promptResolver,
        private readonly AiLanguageTrainerService $trainer,
        private readonly LlmClient $llm,
        private readonly TitleGenerator $titleGen
    ) {
    }

    public function handleMessage(int $userId, string $promptRaw, ?string $sessionToken = null): array
    {
        if (!$sessionToken) {
            throw new \RuntimeException('Session token required');
        }

        $scopeCode = 'cybersecurity';

        $setting = AiSetting::where('is_active', true)->firstOrFail();

        $session = AiChatSession::where('session_token', $sessionToken)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->firstOrFail();

        $normalized = $this->normalizer->normalizeWithMeta($promptRaw, $scopeCode);

        $prompt = (string) ($normalized['normalized_text'] ?? '');
        $languageCode = (string) ($normalized['language_code'] ?? 'id');
        $unknownTerms = (array) ($normalized['unknown_terms'] ?? []);
        $unknownCount = count($unknownTerms);

        $intent = $this->intentResolver->resolve($prompt);
        $intentCode = (string) ($intent->code ?? 'general');
        $intentConfidence = (int) ($intent->confidence ?? 0);

        $validScope = $this->scopeGate->passes($scopeCode, $prompt);

        AiChatMessage::create([
            'session_id' => $session->id,
            'role' => 'user',
            'content' => $promptRaw,
        ]);

        $this->maybeGenerateTitle(
            $session->id,
            $setting,
            $promptRaw,
            $languageCode
        );

        if (!$validScope) {
            $systemPrompt = $this->promptResolver->resolve([
                'scope' => $scopeCode,
                'intent' => $intentCode,
                'behavior' => 'soft_out_of_scope',
                'language' => $languageCode,
            ]);

            [$assistant, $usage] = $this->llm->chat($session, $setting, $systemPrompt);

            $this->saveAssistant($session->id, $assistant);
            $this->touchSession($session->id);
            $this->logUsage(false, 'soft_out_of_scope', $setting, $usage);

            return [
                $session->session_token,
                $assistant,
                200,
                $this->meta('soft_out_of_scope', 'none', 0, $languageCode),
            ];
        }

        [$resourceType, $resources] = $this->resourceResolver->resolve(
            $scopeCode,
            $intentCode,
            $prompt
        );

        $resourcesCount = (int) ($resources?->count() ?? 0);

        if (in_array($resourceType, ['product', 'service', 'ebook'], true)) {
            $systemPrompt = $this->promptResolver->resolve([
                'scope' => $scopeCode,
                'intent' => $intentCode,
                'behavior' => 'resource_list',
                'resource_type' => $resourceType,
                'resources_count' => $resourcesCount,
                'language' => $languageCode,
            ]);

            [$assistant, $usage] = $this->llm->chat($session, $setting, $systemPrompt);

            $this->saveAssistant($session->id, $assistant);
            $this->touchSession($session->id);

            $this->trainer->learnAutomatically(
                $unknownTerms,
                $scopeCode,
                $languageCode,
                $intentConfidence,
                true,
                $userId,
                $session->id
            );

            $this->logUsage(false, null, $setting, $usage);

            return [
                $session->session_token,
                $assistant,
                200,
                $this->meta($intentCode, $resourceType, $resourcesCount, $languageCode),
            ];
        }

        $behavior = $this->behaviorDecider->decide(
            $scopeCode,
            $prompt,
            $intentCode,
            $intentConfidence,
            $unknownCount
        );

        if ($behavior['decision'] === 'guided') {
            $systemPrompt = $this->promptResolver->resolve([
                'scope' => $scopeCode,
                'intent' => $intentCode,
                'behavior' => 'guided',
                'guided_kind' => $behavior['guided_kind'],
                'language' => $languageCode,
            ]);

            [$assistant, $usage] = $this->llm->chat($session, $setting, $systemPrompt);

            $this->saveAssistant($session->id, $assistant);
            $this->touchSession($session->id);

            return [
                $session->session_token,
                $assistant,
                200,
                $this->meta($intentCode, 'none', 0, $languageCode),
            ];
        }

        $systemPrompt = $this->promptResolver->resolve([
            'scope' => $scopeCode,
            'intent' => $intentCode,
            'behavior' => 'default_chat',
            'language' => $languageCode,
        ]);

        [$assistant, $usage] = $this->llm->chat($session, $setting, $systemPrompt);

        $this->saveAssistant($session->id, $assistant);
        $this->touchSession($session->id);

        $this->trainer->learnAutomatically(
            $unknownTerms,
            $scopeCode,
            $languageCode,
            $intentConfidence,
            true,
            $userId,
            $session->id
        );

        $this->logUsage(false, null, $setting, $usage);

        return [
            $session->session_token,
            $assistant,
            200,
            $this->meta($intentCode, 'none', 0, $languageCode),
        ];
    }

    private function maybeGenerateTitle(
        int $sessionId,
        AiSetting $setting,
        string $promptRaw,
        string $languageCode
    ): void {
        $currentTitle = AiChatSession::where('id', $sessionId)->value('title');

        if ($currentTitle !== 'Percakapan Baru') {
            return;
        }

        $title = $this->titleGen->generateFromUserPrompt(
            AiChatSession::findOrFail($sessionId),
            $setting,
            $promptRaw,
            $languageCode
        );

        if (!is_string($title) || trim($title) === '') {
            return;
        }

        AiChatSession::where('id', $sessionId)
            ->where('title', 'Percakapan Baru')
            ->update([
                'title' => mb_strimwidth(trim($title), 0, 80),
            ]);
    }

    private function saveAssistant(int $sessionId, string $content): void
    {
        AiChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'assistant',
            'content' => $content,
        ]);
    }

    private function touchSession(int $sessionId): void
    {
        AiChatSession::where('id', $sessionId)->update([
            'last_activity_at' => now(),
        ]);
    }

    private function meta(string $intent, string $resource, int $count, string $lang): array
    {
        return [
            'scope' => 'cybersecurity',
            'intent' => $intent,
            'resource' => $resource,
            'resources_count' => $count,
            'language' => $lang,
        ];
    }

    private function logUsage(bool $blocked, ?string $reason, AiSetting $setting, array $usage = []): void
    {
        AiUsageLog::create([
            'provider' => $setting->provider,
            'model' => $setting->model,
            'scope_code' => 'cybersecurity',
            'prompt_tokens' => (int) ($usage['prompt_tokens'] ?? 0),
            'completion_tokens' => (int) ($usage['completion_tokens'] ?? 0),
            'total_tokens' => (int) ($usage['total_tokens'] ?? 0),
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            'is_blocked' => false,
            'block_reason' => $reason,
        ]);
    }
}
