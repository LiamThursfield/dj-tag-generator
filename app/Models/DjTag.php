<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property string $service
 * @property string $voice_id
 * @property array<array-key, mixed>|null $voice_settings
 * @property string $format
 * @property string|null $raw_audio_path
 * @property float|null $raw_audio_duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DjTagVersion|null $latestVersion
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DjTagVersion> $versions
 * @property-read int|null $versions_count
 *
 * @method static \Database\Factories\DjTagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereRawAudioDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereRawAudioPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereVoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereVoiceSettings($value)
 *
 * @mixin \Eloquent
 */
class DjTag extends Model
{
    /** @use HasFactory<\Database\Factories\DjTagFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'service',
        'voice_id',
        'voice_settings',
        'format',
        'raw_audio_path',
        'raw_audio_duration',
    ];

    protected function casts(): array
    {
        return [
            'voice_settings' => 'array',
            'raw_audio_duration' => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function versions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DjTagVersion::class);
    }

    public function latestVersion(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DjTagVersion::class)->latestOfMany();
    }

    public function hasRawAudio(): bool
    {
        return ! empty($this->raw_audio_path);
    }
}
