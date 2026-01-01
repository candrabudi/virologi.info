<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cyber_attacks', function (Blueprint $table) {
            $table->id();

            $table->string('attack_id')->unique();

            $table->ipAddress('source_ip');
            $table->ipAddress('destination_ip');

            $table->string('source_country', 100)->nullable();
            $table->string('destination_country', 100)->nullable();

            $table->string('protocol', 20);
            $table->unsignedInteger('source_port')->nullable();
            $table->unsignedInteger('destination_port')->nullable();

            $table->string('attack_type', 100);

            $table->unsignedBigInteger('payload_size_bytes')->nullable();

            $table->string('detection_label', 100);
            $table->decimal('confidence_score', 5, 2)->comment('0.00 - 100.00');

            $table->string('ml_model', 100)->nullable();

            $table->string('affected_system', 150)->nullable();
            $table->string('port_type', 50)->nullable();

            $table->timestamps();

            $table->index(['source_ip', 'destination_ip']);
            $table->index('attack_type');
            $table->index('protocol');
            $table->index('detection_label');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cyber_attacks');
    }
};
