<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dj_tag_presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Nullable for system presets
            $table->string('name');
            $table->string('service')->default('openai');
            $table->string('voice_id');
            $table->json('voice_settings')->nullable();
            $table->json('audio_effects')->nullable();
            $table->boolean('is_public')->default(false); // For system presets
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dj_tag_presets');
    }
};
