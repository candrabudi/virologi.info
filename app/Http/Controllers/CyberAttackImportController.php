<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CyberAttackImportController extends Controller
{
    public function import()
    {
        $filePath = storage_path('app/data/cyber_attacks.csv');

        if (!file_exists($filePath)) {
            return response()->json([
                'status' => false,
                'message' => 'CSV file not found',
            ], 404);
        }

        $handle = fopen($filePath, 'r');

        if (!$handle) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to open CSV file',
            ], 500);
        }

        $header = fgetcsv($handle); // baca header CSV
        $batchSize = 1000;
        $batch = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $batch[] = [
                'attack_id' => $data['Attack ID'],
                'source_ip' => $data['Source IP'],
                'destination_ip' => $data['Destination IP'],
                'source_country' => $data['Source Country'] ?? null,
                'destination_country' => $data['Destination Country'] ?? null,
                'protocol' => $data['Protocol'],
                'source_port' => $data['Source Port'] ?: null,
                'destination_port' => $data['Destination Port'] ?: null,
                'attack_type' => $data['Attack Type'],
                'payload_size_bytes' => $data['Payload Size (bytes)'] ?: null,
                'detection_label' => $data['Detection Label'],
                'confidence_score' => (float) $data['Confidence Score'],
                'ml_model' => $data['ML Model'] ?? null,
                'affected_system' => $data['Affected System'] ?? null,
                'port_type' => $data['Port Type'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                DB::table('cyber_attacks')->upsert(
                    $batch,
                    ['attack_id'], // key unik
                    [
                        'source_ip',
                        'destination_ip',
                        'source_country',
                        'destination_country',
                        'protocol',
                        'source_port',
                        'destination_port',
                        'attack_type',
                        'payload_size_bytes',
                        'detection_label',
                        'confidence_score',
                        'ml_model',
                        'affected_system',
                        'port_type',
                        'updated_at',
                    ] // kolom di-update jika duplikat
                );
                $batch = [];
            }
        }

        // Insert sisa batch
        if (!empty($batch)) {
            DB::table('cyber_attacks')->upsert(
                $batch,
                ['attack_id'],
                [
                    'source_ip',
                    'destination_ip',
                    'source_country',
                    'destination_country',
                    'protocol',
                    'source_port',
                    'destination_port',
                    'attack_type',
                    'payload_size_bytes',
                    'detection_label',
                    'confidence_score',
                    'ml_model',
                    'affected_system',
                    'port_type',
                    'updated_at',
                ]
            );
        }

        fclose($handle);

        return response()->json([
            'status' => true,
            'message' => 'CSV imported successfully (duplicates handled)',
        ]);
    }
}
