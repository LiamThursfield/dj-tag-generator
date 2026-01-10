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
        Schema::table('users', function (Blueprint $table) {
            $table->text('openai_api_key')->nullable()->after('email');
            $table->text('elevenlabs_api_key')->nullable()->after('openai_api_key');
            $table->string('preferred_tts_service')->default('openai')->after('elevenlabs_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['openai_api_key', 'elevenlabs_api_key', 'preferred_tts_service']);
        });
    }
};
