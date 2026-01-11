<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property string $service
 * @property string $voice_id
 * @property array<array-key, mixed>|null $voice_settings
 * @property array<array-key, mixed>|null $audio_effects
 * @property string|null $audio_path
 * @property string $format
 * @property float|null $duration
 * @property string $status
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\DjTagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereAudioEffects($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereAudioPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereVoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereVoiceSettings($value)
 * @property string|null $raw_audio_path
 * @property float|null $raw_audio_duration
 * @property-read \App\Models\DjTagVersion|null $latestVersion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DjTagVersion> $versions
 * @property-read int|null $versions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereRawAudioDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DjTag whereRawAudioPath($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDjTag {}
}

namespace App\Models{
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
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDjTagPreset {}
}

namespace App\Models{
/**
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
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDjTagVersion {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $openai_api_key
 * @property string|null $elevenlabs_api_key
 * @property string $preferred_tts_service
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property \Illuminate\Support\Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DjTagPreset> $djTagPresets
 * @property-read int|null $dj_tag_presets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DjTag> $djTags
 * @property-read int|null $dj_tags_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereElevenlabsApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOpenaiApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePreferredTtsService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

