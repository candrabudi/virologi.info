<?php

namespace App\Services\AgentAi;

use App\Models\AiSetting;

class ScopeGate
{
    public function __construct(
        private readonly LlmClient $llm
    ) {
    }

    public function passes(string $scopeCode, string $prompt): bool
    {
        if ($scopeCode !== 'cybersecurity') {
            return true;
        }

        $prompt = trim($prompt);
        if ($prompt === '') {
            return false;
        }

        $setting = AiSetting::where('is_active', true)->first();
        if (!$setting) {
            return false;
        }

        $systemPrompt = $this->buildClassifierPrompt($prompt);

        [$result] = $this->llm->classify(
            $setting,
            $systemPrompt
        );

        return strtoupper(trim($result)) === 'YES';
    }

    private function buildClassifierPrompt(string $prompt): string
    {
        return <<<PROMPT
You are a strict classifier.

Task:
Determine whether the user's message is related to CYBER SECURITY.

Cyber Security includes topics such as:
- Network security
- Application security
- Cloud security
- Pentesting
- DDoS, DoS, botnet
- Malware, ransomware, phishing
- Vulnerabilities, CVE, exploits
- Authentication, authorization, encryption
- SOC, SIEM, incident response
- Web security (XSS, SQLi, CSRF)

User message:
"{$prompt}"

Rules:
- Answer ONLY with YES or NO
- Do NOT explain
- Do NOT add any other text
PROMPT;
    }
}
