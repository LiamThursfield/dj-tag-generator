<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDjTagVersion
 *
 * @property int $id
 * @property int $dj_tag_id
 * @property int $version_number
 * @property array<array-key, mixed>|null $audio_effects
 * @property string|null $audio_path
 * @property float|null $duration
 * @property string $status
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DjTag $djTag
 *
 * @method static \Database\Factories\DjTagVersionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereAudioEffects($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereAudioPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereDjTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTagVersion whereVersionNumber($value)
 *
 * @mixin \Eloquent
 */
class DjTagVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'dj_tag_id',
        'version_number',
        'audio_effects',
        'audio_path',
        'duration',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'audio_effects' => 'array',
            'duration' => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function djTag(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DjTag::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
