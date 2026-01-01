<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyberAttack extends Model
{
    use HasFactory;

    protected $table = 'cyber_attacks';

    protected $fillable = [
        'attack_id',
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
    ];

    protected $casts = [
        'confidence_score' => 'float',
        'payload_size_bytes' => 'integer',
        'source_port' => 'integer',
        'destination_port' => 'integer',
    ];
}
