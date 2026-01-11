<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDjTagVersion
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

    public function djTag()
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
