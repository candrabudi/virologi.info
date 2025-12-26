<?php

namespace App\Providers;

use App\Services\AgentAi\AgentAiService;
use App\Services\AgentAi\DomainResolver;
use App\Services\AgentAi\IntentResolver;
use App\Services\AgentAi\KeywordExtractor;
use App\Services\AgentAi\LlmClient;
use App\Services\AgentAi\PolicyGate;
use App\Services\AgentAi\PromptNormalizer;
use App\Services\AgentAi\ResourceResolver;
use App\Services\AgentAi\ScopeGate;
use App\Services\AgentAi\SystemPromptBuilder;
use App\Services\AgentAi\TitleGenerator;
use App\Services\AiLanguageNormalizerService;
use App\Services\AiLanguageTrainerService;
use Illuminate\Support\ServiceProvider;

class AgentAiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AiLanguageNormalizerService::class);
        $this->app->singleton(AiLanguageTrainerService::class);

        $this->app->singleton(PromptNormalizer::class, fn ($app) => new PromptNormalizer(
            $app->make(AiLanguageNormalizerService::class)
        )
        );

        $this->app->singleton(PolicyGate::class);
        $this->app->singleton(ScopeGate::class, fn ($app) => new ScopeGate(
            $app->make(PromptNormalizer::class)
        )
        );

        $this->app->singleton(IntentResolver::class);
        $this->app->singleton(DomainResolver::class);
        $this->app->singleton(KeywordExtractor::class);

        $this->app->singleton(ResourceResolver::class, fn ($app) => new ResourceResolver(
            $app->make(KeywordExtractor::class)
        )
        );

        $this->app->singleton(SystemPromptBuilder::class);
        $this->app->singleton(LlmClient::class);
        $this->app->singleton(TitleGenerator::class);

        $this->app->singleton(AgentAiService::class, fn ($app) => new AgentAiService(
            $app->make(PromptNormalizer::class),
            $app->make(PolicyGate::class),
            $app->make(ScopeGate::class),
            $app->make(IntentResolver::class),
            $app->make(DomainResolver::class),
            $app->make(ResourceResolver::class),
            $app->make(SystemPromptBuilder::class),
            $app->make(LlmClient::class),
            $app->make(TitleGenerator::class),
            $app->make(AiLanguageTrainerService::class)
        )
        );
    }
}
