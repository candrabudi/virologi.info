<?php

namespace App\Services\AgentAi;

use App\Models\AiResourceRoute;
use App\Models\CyberSecurityService;
use App\Models\Ebook;
use App\Models\Product;

class ResourceResolver
{
    public function resolve(string $scope, string $intent, string $prompt): array
    {
        $type = match ($intent) {
            'product' => 'product',
            'service' => 'service',
            'ebook' => 'ebook',
            default => 'none',
        };

        if ($type === 'none') {
            return ['none', collect()];
        }

        $routes = AiResourceRoute::where('scope_code', $scope)
            ->where('resource_type', $type)
            ->where('is_active', true)
            ->get();

        $ids = [];

        foreach ($routes as $r) {
            if (str_contains($prompt, $r->keyword)) {
                $ids[$r->resource_id] = ($ids[$r->resource_id] ?? 0) + $r->weight;
            }
        }

        arsort($ids);
        $ids = array_keys($ids);

        return match ($type) {
            'product' => [$type, Product::whereIn('id', $ids)->get()],
            'service' => [$type, CyberSecurityService::whereIn('id', $ids)->get()],
            'ebook' => [$type, Ebook::whereIn('id', $ids)->get()],
        };
    }
}
