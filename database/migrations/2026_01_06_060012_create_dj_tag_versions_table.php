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
        Schema::create('dj_tag_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dj_tag_id')->constrained()->cascadeOnDelete();
            $table->integer('version_number')->default(1);
            $table->json('audio_effects')->nullable(); // pitch, reverb, etc.
            $table->string('audio_path')->nullable();
            $table->float('duration')->nullable(); // in seconds
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['dj_tag_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dj_tag_versions');
    }
};
