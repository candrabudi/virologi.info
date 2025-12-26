<?php

namespace App\Services\AgentAi;

class LanguageDetector
{
    public function detect(array $tokens): string
    {
        $scores = ['id' => 0, 'en' => 0, 'jv' => 0, 'su' => 0];

        $map = [
            'id' => ['aku', 'saya', 'ingin', 'belajar', 'bagaimana', 'produk', 'layanan'],
            'en' => ['i', 'want', 'learn', 'how', 'product', 'service', 'security'],
            'jv' => ['aku', 'sinau', 'piye', 'arep'],
            'su' => ['abdi', 'diajar', 'kumaha', 'hoyong'],
        ];

        foreach ($tokens as $t) {
            foreach ($map as $lang => $words) {
                if (in_array($t, $words, true)) {
                    $scores[$lang] += 2;
                }
            }
        }

        arsort($scores);
        $lang = array_key_first($scores);

        return $scores[$lang] >= 2 ? $lang : 'unknown';
    }
}
