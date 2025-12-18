<?php

namespace Database\Seeders;

use App\Models\AiContext;
use App\Models\AiPromptBinding;
use App\Models\AiPromptTemplate;
use App\Models\AiRule;
use Illuminate\Database\Seeder;

class AiMasterSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        \DB::table('ai_prompt_bindings')->truncate();
        \DB::table('ai_rules')->truncate();
        \DB::table('ai_prompt_templates')->truncate();
        \DB::table('ai_contexts')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $contexts = [
            'general' => AiContext::create([
                'code' => 'general',
                'name' => 'General Cybersecurity',
                'category' => 'cybersecurity',
                'use_internal_source' => false,
                'is_active' => true,
            ]),
            'learning' => AiContext::create([
                'code' => 'learning',
                'name' => 'Belajar Cybersecurity',
                'category' => 'cybersecurity',
                'use_internal_source' => true,
                'is_active' => true,
            ]),
            'product' => AiContext::create([
                'code' => 'product',
                'name' => 'Produk & Ebook Cybersecurity',
                'category' => 'cybersecurity',
                'use_internal_source' => true,
                'is_active' => true,
            ]),
            'service' => AiContext::create([
                'code' => 'service',
                'name' => 'Layanan & Konsultasi Keamanan',
                'category' => 'cybersecurity',
                'use_internal_source' => true,
                'is_active' => true,
            ]),
        ];

        $templates = [
            'core_identity' => AiPromptTemplate::create([
                'type' => 'core_identity',
                'content' => 'Anda adalah AI Cybersecurity Assistant profesional dalam ekosistem Security Portal. Gunakan bahasa Indonesia yang jelas, edukatif, dan etis.',
                'is_active' => true,
            ]),
            'learning_structure' => AiPromptTemplate::create([
                'type' => 'learning_structure',
                'content' => 'Gunakan pendekatan bertahap: dasar, menengah, lanjutan. Tutup dengan pertanyaan klarifikasi.',
                'is_active' => true,
            ]),
            'ethical_guard' => AiPromptTemplate::create([
                'type' => 'ethical_guard',
                'content' => 'Tolak panduan ilegal atau eksploitasi kriminal. Arahkan ke defensive & legal.',
                'is_active' => true,
            ]),
            'product_pitch' => AiPromptTemplate::create([
                'type' => 'product_pitch',
                'content' => 'Rekomendasikan ebook, modul, atau produk digital secara informatif tanpa memaksa.',
                'is_active' => true,
            ]),
            'service_pitch' => AiPromptTemplate::create([
                'type' => 'service_pitch',
                'content' => 'Tawarkan layanan profesional secara relevan dan kontekstual.',
                'is_active' => true,
            ]),
            'clarifying_question' => AiPromptTemplate::create([
                'type' => 'clarifying_question',
                'content' => 'Ajukan satu pertanyaan lanjutan yang singkat dan relevan.',
                'is_active' => true,
            ]),
            'soft_cta' => AiPromptTemplate::create([
                'type' => 'soft_cta',
                'content' => 'Gunakan ajakan halus untuk eksplorasi lanjutan.',
                'is_active' => true,
            ]),
        ];

        $bindings = [
            'general' => ['core_identity', 'ethical_guard', 'soft_cta'],
            'learning' => ['core_identity', 'ethical_guard', 'learning_structure', 'product_pitch', 'clarifying_question', 'soft_cta'],
            'product' => ['core_identity', 'product_pitch', 'clarifying_question', 'soft_cta'],
            'service' => ['core_identity', 'service_pitch', 'clarifying_question', 'soft_cta'],
        ];

        foreach ($bindings as $context => $types) {
            foreach ($types as $type) {
                AiPromptBinding::create([
                    'ai_context_id' => $contexts[$context]->id,
                    'ai_prompt_template_id' => $templates[$type]->id,
                    'is_active' => true,
                ]);
            }
        }

        AiRule::insert([
            [
                'type' => 'keyword',
                'value' => 'hack akun',
                'category' => 'cybersecurity',
                'is_active' => true,
                'note' => 'Illegal activity',
            ],
            [
                'type' => 'regex',
                'value' => '(carding|ddos for hire|bypass)',
                'category' => 'cybersecurity',
                'is_active' => true,
                'note' => 'High risk intent',
            ],
        ]);
    }
}
