<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EbookSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('ebooks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('ebooks')->insert([
            [
                'uuid' => Str::uuid(),
                'slug' => 'fundamental-cybersecurity-untuk-pemula',
                'title' => 'Fundamental Cybersecurity untuk Pemula',
                'subtitle' => 'Panduan Lengkap Memulai Karier Keamanan Siber',
                'summary' => 'Ebook ini membahas konsep dasar cybersecurity, ancaman umum, dan mindset keamanan yang wajib dimiliki pemula.',
                'content' => null,
                'level' => 'beginner',
                'topic' => 'general',
                'chapters' => json_encode([
                    'Pengantar Cybersecurity',
                    'Ancaman Siber Modern',
                    'Konsep CIA Triad',
                    'Keamanan Dasar Sistem & Jaringan',
                    'Etika dan Legalitas',
                ]),
                'learning_objectives' => json_encode([
                    'Memahami dasar cybersecurity',
                    'Mengenal jenis serangan siber',
                    'Membangun security mindset',
                ]),
                'ai_keywords' => json_encode([
                    'belajar cybersecurity',
                    'cybersecurity pemula',
                    'security fundamentals',
                    'career cybersecurity',
                ]),
                'cover_image' => 'ebooks/covers/cybersecurity-basic.jpg',
                'file_path' => 'ebooks/files/fundamental-cybersecurity.pdf',
                'file_type' => 'pdf',
                'page_count' => 120,
                'author' => 'Virologi Security Team',
                'published_at' => Carbon::parse('2024-01-10'),
                'meta_title' => 'Ebook Fundamental Cybersecurity untuk Pemula',
                'meta_description' => 'Panduan belajar cybersecurity dari nol untuk pemula.',
                'meta_keywords' => json_encode([
                    'cybersecurity',
                    'belajar keamanan siber',
                    'ebook cybersecurity',
                ]),
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'uuid' => Str::uuid(),
                'slug' => 'network-security-dasar-hingga-menengah',
                'title' => 'Network Security: Dasar hingga Menengah',
                'subtitle' => 'Melindungi Infrastruktur Jaringan Modern',
                'summary' => 'Fokus pada konsep keamanan jaringan, firewall, IDS/IPS, dan segmentasi jaringan.',
                'content' => null,
                'level' => 'intermediate',
                'topic' => 'network_security',
                'chapters' => json_encode([
                    'Dasar Jaringan & Protokol',
                    'Ancaman Jaringan',
                    'Firewall & Network Segmentation',
                    'IDS/IPS',
                    'Monitoring & Logging',
                ]),
                'learning_objectives' => json_encode([
                    'Memahami keamanan jaringan',
                    'Mengkonfigurasi firewall dasar',
                    'Mengenali serangan jaringan',
                ]),
                'ai_keywords' => json_encode([
                    'network security',
                    'firewall',
                    'ids ips',
                    'keamanan jaringan',
                ]),
                'cover_image' => 'ebooks/covers/network-security.jpg',
                'file_path' => 'ebooks/files/network-security.pdf',
                'file_type' => 'pdf',
                'page_count' => 180,
                'author' => 'Virologi Security Team',
                'published_at' => Carbon::parse('2024-02-20'),
                'meta_title' => 'Ebook Network Security',
                'meta_description' => 'Panduan keamanan jaringan untuk praktisi IT.',
                'meta_keywords' => json_encode([
                    'network security',
                    'firewall',
                    'ids',
                ]),
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'uuid' => Str::uuid(),
                'slug' => 'soc-analyst-handbook',
                'title' => 'SOC Analyst Handbook',
                'subtitle' => 'Panduan Praktis Menjadi Security Operations Analyst',
                'summary' => 'Ebook ini membahas workflow SOC, SIEM, incident handling, dan threat monitoring.',
                'content' => null,
                'level' => 'intermediate',
                'topic' => 'soc',
                'chapters' => json_encode([
                    'Pengenalan SOC',
                    'Peran & Tanggung Jawab SOC Analyst',
                    'SIEM & Log Analysis',
                    'Incident Detection',
                    'Response & Escalation',
                ]),
                'learning_objectives' => json_encode([
                    'Memahami operasional SOC',
                    'Menggunakan SIEM',
                    'Menangani insiden keamanan',
                ]),
                'ai_keywords' => json_encode([
                    'soc analyst',
                    'siem',
                    'incident response',
                    'blue team',
                ]),
                'cover_image' => 'ebooks/covers/soc-analyst.jpg',
                'file_path' => 'ebooks/files/soc-analyst-handbook.pdf',
                'file_type' => 'pdf',
                'page_count' => 210,
                'author' => 'Virologi Blue Team',
                'published_at' => Carbon::parse('2024-03-15'),
                'meta_title' => 'SOC Analyst Handbook',
                'meta_description' => 'Panduan lengkap untuk SOC Analyst.',
                'meta_keywords' => json_encode([
                    'soc',
                    'siem',
                    'incident response',
                ]),
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'uuid' => Str::uuid(),
                'slug' => 'pentesting-web-application',
                'title' => 'Web Application Pentesting',
                'subtitle' => 'Pendekatan Legal & Etis dalam Pengujian Keamanan',
                'summary' => 'Membahas metodologi OWASP Top 10 dan teknik pentesting aplikasi web secara legal.',
                'content' => null,
                'level' => 'advanced',
                'topic' => 'pentest',
                'chapters' => json_encode([
                    'Etika & Legalitas Pentesting',
                    'OWASP Top 10',
                    'Reconnaissance',
                    'Exploitation & Validation',
                    'Reporting',
                ]),
                'learning_objectives' => json_encode([
                    'Memahami metodologi pentesting',
                    'Mengidentifikasi celah web',
                    'Menyusun laporan pentest',
                ]),
                'ai_keywords' => json_encode([
                    'pentesting',
                    'owasp',
                    'web security',
                    'ethical hacking',
                ]),
                'cover_image' => 'ebooks/covers/pentest-web.jpg',
                'file_path' => 'ebooks/files/web-pentesting.pdf',
                'file_type' => 'pdf',
                'page_count' => 260,
                'author' => 'Virologi Red Team',
                'published_at' => Carbon::parse('2024-04-05'),
                'meta_title' => 'Web Application Pentesting',
                'meta_description' => 'Panduan pentesting web secara etis dan profesional.',
                'meta_keywords' => json_encode([
                    'pentest',
                    'owasp',
                    'web security',
                ]),
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
