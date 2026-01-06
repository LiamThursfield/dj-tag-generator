<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'openai_api_key',
        'elevenlabs_api_key',
        'preferred_tts_service',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
        'openai_api_key',
        'elevenlabs_api_key',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'openai_api_key' => 'encrypted',
            'elevenlabs_api_key' => 'encrypted',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function djTags()
    {
        return $this->hasMany(DjTag::class);
    }

    public function djTagPresets()
    {
        return $this->hasMany(DjTagPreset::class);
    }

    public function getApiKeyForService(string $service): ?string
    {
        return match ($service) {
            'openai' => $this->openai_api_key,
            'elevenlabs' => $this->elevenlabs_api_key,
            default => null,
        };
    }

    public function hasServiceConfigured(string $service): bool
    {
        return !empty($this->getApiKeyForService($service));
    }
}
