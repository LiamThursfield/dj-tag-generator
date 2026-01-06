<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dj_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('text');
            $table->string('service')->default('openai'); // openai, elevenlabs
            $table->string('voice_id');
            $table->json('voice_settings')->nullable(); // speed, stability, etc.
            $table->json('audio_effects')->nullable(); // pitch, reverb, etc.
            $table->string('audio_path')->nullable();
            $table->string('format')->default('mp3');
            $table->float('duration')->nullable(); // in seconds
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dj_tags');
    }
};
