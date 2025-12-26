<?php

namespace App\Services\AgentAi;

use App\Models\AiSystemPrompt;

class PromptResolverService
{
    public function resolve(array $context): string
    {
        $scope = $context['scope'] ?? 'cybersecurity';

        $basePrompt = $this->baseDomainPrompt($scope);

        $extraPrompt = $this->resolveOptionalPrompt($context);

        return trim($basePrompt."\n\n".$extraPrompt);
    }

    protected function baseDomainPrompt(string $scope): string
    {
        $prompt = AiSystemPrompt::query()
            ->where('scope_code', $scope)
            ->whereNull('intent_code')
            ->whereNull('behavior')
            ->whereNull('resource_type')
            ->where('is_active', true)
            ->orderByDesc('priority')
            ->first();

        if ($prompt) {
            return trim($prompt->content);
        }

        return match ($scope) {
            'cybersecurity' => <<<PROMPT
Kamu adalah AI seperti ChatGPT, namun khusus di bidang Cyber Security dan Secure Software Engineering.

ATURAN UTAMA:
- Semua jawaban HARUS relevan dengan cyber security
- Jika pertanyaan umum, tarik ke sudut pandang security
- Jangan menolak secara keras

PERCAKAPAN:
- Anggap semua pertanyaan adalah lanjutan konteks sebelumnya
- Jangan mengulang penjelasan kecuali diminta

CODING:
- Semua pertanyaan kode, server, cloud, API BOLEH
- Gunakan best practice dan secure-by-default

GAYA:
- Natural seperti ChatGPT
- Profesional tapi santai
PROMPT,
            default => 'You are an AI assistant.',
        };
    }

    protected function resolveOptionalPrompt(array $context): string
    {
        $intent = $context['intent'] ?? null;
        $behavior = $context['behavior'] ?? null;
        $resourceType = $context['resource_type'] ?? null;

        if (!$intent && !$behavior && !$resourceType) {
            return '';
        }

        $prompt = AiSystemPrompt::query()
            ->where('is_active', true)
            ->where(function ($q) use ($intent, $behavior, $resourceType) {
                if ($intent) {
                    $q->where('intent_code', $intent);
                }

                if ($behavior) {
                    $q->where('behavior', $behavior);
                }

                if ($resourceType) {
                    $q->where('resource_type', $resourceType);
                }
            })
            ->orderByDesc('priority')
            ->first();

        return $prompt ? trim($this->hydrate($prompt->content, $context)) : '';
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
}
