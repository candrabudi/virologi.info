<?php

namespace App\Services\AgentAi;

use App\Models\AiChatSession;
use App\Models\AiSetting;

class TitleGenerator
{
    public function generate(AiChatSession $session, AiSetting $setting): string
    {
        return mb_substr($session->messages()->first()->content ?? 'Cyber Security', 0, 40);
    }
}
