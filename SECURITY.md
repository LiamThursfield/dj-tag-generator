# Security & Cost Control Guidelines - DJ Tag Generator

## Overview

This document outlines security measures and cost controls implemented to prevent abuse and minimize operational costs. Since users bring their own API keys (BYOK model), these controls protect both the platform and individual users.

---

## Cost Control Measures

### 1. Text-to-Speech Limits

**Character Limits:**
- **Max text length**: 500 characters (configurable via `AUDIO_MAX_TEXT_LENGTH`)
- **Typical DJ tag**: 20-100 characters
- **Rationale**: Prevents users from accidentally or maliciously generating expensive long-form content

**Cost Impact:**
```
OpenAI TTS: $15 per 1M characters
- 500 char limit = $0.0075 max per tag
- Typical 50 char tag = $0.00075

ElevenLabs: ~$0.30 per 1000 characters  
- 500 char limit = $0.15 max per tag
- Typical 50 char tag = $0.015
```

### 2. Rate Limiting

**Per-User Limits:**
- **10 tags per hour** (configurable via `AUDIO_MAX_PER_HOUR`)
- **50 tags per day** (configurable via `AUDIO_MAX_PER_DAY`)

**Rationale:**
- Prevents accidental runaway processes
- Limits damage from compromised accounts
- Reasonable for legitimate DJ tag creation

**Implementation:**
```php
// config/audio.php
'rate_limiting' => [
    'enabled' => env('AUDIO_RATE_LIMITING_ENABLED', true),
    'max_per_hour' => env('AUDIO_MAX_PER_HOUR', 10),
    'max_per_day' => env('AUDIO_MAX_PER_DAY', 50),
],
```

### 3. Audio Duration Limits

**Max Duration**: 10 seconds (configurable via `AUDIO_MAX_DURATION`)

**Rationale:**
- DJ tags are typically 2-5 seconds
- Prevents generation of long-form audio content
- Reduces TTS API costs
- Limits storage and bandwidth usage

### 4. File Size Limits

**Upload Limit**: 5MB (down from 100MB)
**Max Generated File**: 5MB

**Rationale:**
- Typical DJ tag MP3 (192kbps, 5 seconds) = ~120KB
- Even with effects, files rarely exceed 500KB
- 5MB provides 10x safety margin
- Prevents bandwidth abuse

---

## Security Measures

### 1. Input Validation

**Text Input:**
```php
// Validate text length
if (mb_strlen($text) > config('audio.max_text_length')) {
    throw new ValidationException('Text exceeds maximum length');
}

// Sanitize input (prevent injection)
$text = strip_tags($text);
$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
```

**File Uploads:**
```php
// Validate file size
if ($file->getSize() > config('audio.max_file_size')) {
    throw new ValidationException('File too large');
}

// Validate file type
$allowedFormats = config('audio.allowed_formats');
if (!in_array($file->extension(), $allowedFormats)) {
    throw new ValidationException('Invalid file format');
}
```

### 2. API Key Protection

**Storage:**
- User API keys stored with Laravel's encrypted casting
- Separate field per TTS service:
  - `openai_api_key` - OpenAI TTS API key
  - `elevenlabs_api_key` - ElevenLabs API key
- Never logged or exposed in error messages
- Separate encryption key from application key

**Implementation:**
```php
// app/Models/User.php
protected $casts = [
    'openai_api_key' => 'encrypted',
    'elevenlabs_api_key' => 'encrypted',
];

protected $hidden = [
    'password',
    'openai_api_key',
    'elevenlabs_api_key',
];
```

**Validation:**
```php
// Validate API key before storing
$service = app(TtsServiceFactory::class)->make($serviceName, $apiKey);
if (!$service->validateCredentials($apiKey)) {
    throw new ValidationException('Invalid API key');
}
```

**User Flexibility:**
- Users can configure OpenAI only
- Users can configure ElevenLabs only
- Users can configure both services
- Switch between services per tag generation

### 3. Resource Limits

**PHP Configuration** (`docker/8.5/php.ini`):
```ini
; Memory limit - sufficient but not excessive
memory_limit = 256M

; Execution time - prevents long-running processes
max_execution_time = 60
max_input_time = 60

; Upload limits - appropriate for use case
post_max_size = 5M
upload_max_filesize = 5M
```

**FFmpeg Limits** (`config/audio.php`):
```php
'ffmpeg' => [
    'timeout' => 60,      // Max 60 seconds processing
    'threads' => 2,       // Limit CPU usage
],
```

### 4. Queue Job Protection

**Timeouts:**
```php
// app/Jobs/TagGenerationJob.php
public $timeout = 120;      // 2 minutes max
public $tries = 1;          // Don't retry on failure
public $maxExceptions = 1;  // Fail fast
```

**Rationale:**
- Prevents jobs from running indefinitely
- Limits resource consumption
- Fails fast on errors to prevent cost accumulation

### 5. Storage Security

**File Cleanup:**
- Automatic deletion after 30 days (configurable)
- Prevents unlimited storage growth
- Reduces storage costs

**Access Control:**
```php
// Use signed URLs for downloads
$url = Storage::disk('minio')->temporaryUrl(
    $tag->audio_path,
    now()->addMinutes(5)
);
```

**Rationale:**
- Prevents unauthorized access
- Limits URL validity period
- Prevents hotlinking/bandwidth theft

---

## Monitoring & Alerts

### Recommended Monitoring

**Cost Tracking:**
```php
// Track TTS usage per user
$user->increment('tts_characters_used', mb_strlen($text));
$user->increment('tts_cost_cents', $estimatedCost * 100);

// Alert if user exceeds threshold
if ($user->tts_cost_cents > 10000) { // $100
    // Send alert email
}
```

**Rate Limit Violations:**
```php
// Log rate limit hits
Log::warning('Rate limit exceeded', [
    'user_id' => $user->id,
    'ip' => $request->ip(),
    'endpoint' => $request->path(),
]);

// Temporary ban after repeated violations
if ($violations > 5) {
    $user->banned_until = now()->addHours(24);
}
```

**Resource Usage:**
```php
// Monitor FFmpeg processing time
$start = microtime(true);
$processor->applyEffects($path, $effects);
$duration = microtime(true) - $start;

if ($duration > 30) {
    Log::warning('Slow audio processing', [
        'duration' => $duration,
        'effects' => $effects,
    ]);
}
```

---

## Attack Vectors & Mitigations

### 1. API Key Theft

**Risk**: Stolen API keys used to generate expensive content

**Mitigation:**
- Encrypted storage
- Rate limiting per user
- Cost alerts
- Ability to revoke/rotate keys

### 2. Bandwidth Abuse

**Risk**: Generating large files to consume bandwidth

**Mitigation:**
- 10-second duration limit
- 5MB file size limit
- Signed URLs with expiration
- CDN with bandwidth limits

### 3. Storage Abuse

**Risk**: Generating thousands of files to fill storage

**Mitigation:**
- Rate limiting (10/hour, 50/day)
- Automatic cleanup after 30 days
- Storage quotas per user
- Monitor storage growth

### 4. CPU/Memory Exhaustion

**Risk**: Complex effects causing high resource usage

**Mitigation:**
- FFmpeg timeout (60 seconds)
- Thread limit (2 threads)
- Memory limit (256MB)
- Queue job timeout (120 seconds)

### 5. Injection Attacks

**Risk**: Malicious input in text or filenames

**Mitigation:**
- Input sanitization (strip_tags, htmlspecialchars)
- Parameterized queries (Eloquent)
- File type validation
- Path traversal prevention

---

## Configuration Checklist

### Production Deployment

- [ ] Set `AUDIO_RATE_LIMITING_ENABLED=true`
- [ ] Configure appropriate rate limits for your use case
- [ ] Set up cost monitoring and alerts
- [ ] Enable storage cleanup cron job
- [ ] Configure CDN with bandwidth limits
- [ ] Set up error tracking (Sentry, Bugsnag)
- [ ] Enable audit logging for API key changes
- [ ] Configure backup strategy
- [ ] Set up uptime monitoring
- [ ] Review and adjust limits based on usage patterns

### Security Hardening

- [ ] Use HTTPS only (enforce in production)
- [ ] Enable CSRF protection (Laravel default)
- [ ] Configure CORS appropriately
- [ ] Use strong session encryption
- [ ] Rotate application keys regularly
- [ ] Keep dependencies updated
- [ ] Enable security headers (Helmet)
- [ ] Configure rate limiting at nginx/load balancer level
- [ ] Use separate database user with minimal permissions
- [ ] Enable database query logging in production

---

## Cost Estimation Tool

**Helper Function:**
```php
// app/Helpers/CostEstimator.php
class CostEstimator
{
    public static function estimateTagCost(string $text, string $service): float
    {
        $charCount = mb_strlen($text);
        
        return match($service) {
            'openai' => ($charCount / 1_000_000) * 15,      // $15/1M chars
            'elevenlabs' => ($charCount / 1_000) * 0.30,    // $0.30/1k chars
            default => 0,
        };
    }
    
    public static function estimateMonthlyCost(int $tagsPerDay, int $avgChars, string $service): float
    {
        $monthlyChars = $tagsPerDay * $avgChars * 30;
        
        return match($service) {
            'openai' => ($monthlyChars / 1_000_000) * 15,
            'elevenlabs' => ($monthlyChars / 1_000) * 0.30,
            default => 0,
        };
    }
}
```

**Example Usage:**
```php
// 50 tags/day, 50 chars each, OpenAI
$monthlyCost = CostEstimator::estimateMonthlyCost(50, 50, 'openai');
// Result: ~$1.13/month

// 50 tags/day, 50 chars each, ElevenLabs  
$monthlyCost = CostEstimator::estimateMonthlyCost(50, 50, 'elevenlabs');
// Result: ~$22.50/month
```

---

## Recommended Limits by Use Case

### Individual DJ (Hobby)
```env
AUDIO_MAX_TEXT_LENGTH=200
AUDIO_MAX_PER_HOUR=5
AUDIO_MAX_PER_DAY=20
```

### Professional DJ
```env
AUDIO_MAX_TEXT_LENGTH=500
AUDIO_MAX_PER_HOUR=10
AUDIO_MAX_PER_DAY=50
```

### DJ Agency/Studio
```env
AUDIO_MAX_TEXT_LENGTH=500
AUDIO_MAX_PER_HOUR=25
AUDIO_MAX_PER_DAY=200
```

---

## Emergency Response

### If Costs Spike Unexpectedly

1. **Immediate Actions:**
   ```bash
   # Disable tag generation temporarily
   php artisan down --message="Maintenance in progress"
   
   # Check recent activity
   php artisan tinker
   >>> Tag::where('created_at', '>', now()->subHours(24))->count()
   
   # Identify heavy users
   >>> User::withCount('tags')->orderBy('tags_count', 'desc')->take(10)->get()
   ```

2. **Investigate:**
   - Check logs for unusual patterns
   - Review recent API key changes
   - Check for failed jobs (might be retrying)
   - Verify rate limiting is working

3. **Mitigate:**
   - Temporarily reduce rate limits
   - Suspend suspicious accounts
   - Revoke compromised API keys
   - Clear failed job queue

4. **Prevent:**
   - Implement stricter rate limits
   - Add cost caps per user
   - Improve monitoring and alerts
   - Review and update security measures

---

## Summary

**Key Principles:**
1. **Defense in Depth**: Multiple layers of protection
2. **Fail Secure**: Default to restrictive settings
3. **Monitor Everything**: Track usage, costs, and errors
4. **Limit Blast Radius**: Per-user limits prevent platform-wide issues
5. **User Responsibility**: BYOK model means users control their costs

**Default Settings Balance:**
- Generous enough for legitimate use
- Restrictive enough to prevent abuse
- Configurable for different use cases
- Cost-conscious by default

All limits are configurable via environment variables, allowing adjustment based on real-world usage patterns and cost tolerance.
