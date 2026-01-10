<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $service
 * @property string $voice_id
 * @property array<array-key, mixed>|null $voice_settings
 * @property array<array-key, mixed>|null $audio_effects
 * @property bool $is_public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 *
 * @method static \Database\Factories\DjTagPresetFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset forUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereAudioEffects($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereVoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagPreset whereVoiceSettings($value)
 *
 * @mixin \Eloquent
 */
class DjTagPreset extends Model
{
    /** @use HasFactory<\Database\Factories\DjTagPresetFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'voice_settings' => 'array',
            'audio_effects' => 'array',
            'is_public' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
