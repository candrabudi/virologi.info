<?php

namespace App\Services\AgentAi;

class SystemPromptBuilder
{
    public function build(): string
    {
        return implode("\n", [
            'You are a cybersecurity assistant.',
            'Only discuss cybersecurity topics.',
            'Do not provide illegal hacking instructions.',
            'Use database-provided resources only.',
            'Be clear, safe, and helpful.',
        ]);
    }
}
