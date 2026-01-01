<?php

namespace App\Http\Controllers;

use App\Models\CyberAttack;
use Illuminate\Http\JsonResponse;

class AttackSimulationController extends Controller
{
    /**
     * Master nodes (mapping country → map).
     */
    private array $nodes = [
        ['name' => 'Indonesia', 'lat' => -6.2, 'lng' => 106.8],
        ['name' => 'China', 'lat' => 39.9, 'lng' => 116.4],
        ['name' => 'USA', 'lat' => 40.7, 'lng' => -74.0],
        ['name' => 'Germany', 'lat' => 50.1, 'lng' => 8.6],
        ['name' => 'Singapore', 'lat' => 1.3, 'lng' => 103.8],
        ['name' => 'Russia', 'lat' => 55.7, 'lng' => 37.6],
        ['name' => 'Japan', 'lat' => 35.6, 'lng' => 139.6],
        ['name' => 'France', 'lat' => 48.8, 'lng' => 2.3],
        ['name' => 'Australia', 'lat' => -33.8, 'lng' => 151.2],
        ['name' => 'Brazil', 'lat' => -23.5, 'lng' => -46.6],
    ];

    /**
     * GET /attack/nodes.
     */
    public function nodes(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'nodes' => $this->nodes,
        ]);
    }

    /**
     * GET /attack/fire
     * Ambil 1 DATA ACAK dari ±90rb data.
     */
    public function fire(): JsonResponse
    {
        $count = rand(2, 6);

        $attacks = CyberAttack::inRandomOrder()
            ->limit($count)
            ->get();

        if ($attacks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No cyber attack data available',
            ], 404);
        }

        $payload = [];

        foreach ($attacks as $attack) {
            $srcNode = $this->resolveNode($attack->source_country);
            $dstNode = $this->resolveNode($attack->destination_country ?? 'Indonesia');
            $isHighRisk = stripos($attack->detection_label, 'HIGH') !== false;

            $payload[] = [
                'attack_id' => $attack->attack_id,
                'src' => $srcNode,
                'dst' => $dstNode,
                'type' => $attack->attack_type,
                'proto' => $attack->protocol,
                'action' => $isHighRisk ? 'Blocked' : 'Allowed',
                'severity' => $attack->detection_label,
                'color' => $isHighRisk ? '#ff1e6d' : '#10b981',
                'size' => number_format($attack->payload_size_bytes ?? 0).' bytes',
                'ip_src' => $attack->source_ip,
                'ip_dst' => $attack->destination_ip,
                'confidence' => $attack->confidence_score,
                'ml_model' => $attack->ml_model,
                'affected_system' => $attack->affected_system,
                'port_type' => $attack->port_type,
                'timestamp' => $attack->created_at->toIso8601String(),
                'time' => $attack->created_at->format('H:i:s'),
            ];
        }

        return response()->json([
            'success' => true,
            'attacks' => $payload,
        ]);
    }

    /**
     * Resolve country → node lat/lng.
     */
    private function resolveNode(?string $country): array
    {
        if (!$country) {
            return $this->nodes[0];
        }

        foreach ($this->nodes as $node) {
            if (stripos($country, $node['name']) !== false) {
                return $node;
            }
        }

        return $this->nodes[0];
    }
}
