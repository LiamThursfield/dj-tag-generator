# DJ Tag Generator - Technical Documentation

## Project Overview

The DJ Tag Generator is a Laravel 12 application that enables DJs and music producers to create professional audio tags (voice-overs) for their tracks and DJ sets. The application uses a modern tech stack with Vue 3, Inertia.js, and TailwindCSS for the frontend, and Laravel 12 with Fortify for authentication on the backend.

## Architecture

### Application Type
- **Full-stack SPA**: Using Inertia.js to bridge Laravel and Vue.js
- **Monolithic**: Single Laravel application serving both API and frontend
- **Queue-based**: Async processing for audio generation tasks

### Key Design Patterns
- **Action Classes**: Single-purpose classes for business logic
- **Form Requests**: Dedicated validation classes
- **Eloquent Resources**: API response transformation
- **Repository Pattern**: (To be implemented for audio processing)

## Technology Stack Details

### Backend Stack
- **Laravel 12**: Latest Laravel with streamlined structure
- **PHP 8.2+**: Modern PHP with constructor property promotion, enums, etc.
- **Inertia Laravel 2.x**: Server-side routing with SPA experience
- **Laravel Fortify**: Headless authentication backend
- **Laravel Wayfinder**: Type-safe routing between Laravel and TypeScript
- **Laravel Tinker**: REPL for debugging
- **Pest 4**: Modern testing framework with browser testing support

### Frontend Stack
- **Vue 3.5**: Composition API, script setup syntax
- **Inertia Vue 3 2.x**: Vue adapter for Inertia.js
- **TypeScript 5.2**: Type safety across the frontend
- **TailwindCSS 4.x**: Utility-first CSS framework
- **Reka UI**: Headless UI components
- **Lucide Vue**: Icon library
- **VueUse**: Composition utilities

### Development Tools
- **Laravel Sail**: Docker-based local development
- **Vite**: Fast frontend build tool with HMR
- **Laravel Pint**: PHP code formatter (PSR-12)
- **ESLint 9**: JavaScript/TypeScript linting
- **Prettier 3**: Code formatting for JS/TS/Vue
- **Laravel Boost**: MCP server for enhanced AI development

## Database Schema

### Planned Models

#### User
- Standard Laravel user model
- Authentication via Fortify
- Relationships: hasMany tags, hasMany favorites

#### Tag (DjTag)
- `id`: Primary key
- `user_id`: Foreign key to users
- `name`: Tag name/phrase
- `voice_type`: Selected voice option
- `effects`: JSON array of applied effects
- `audio_path`: Path to generated audio file
- `duration`: Audio duration in seconds
- `status`: pending|processing|completed|failed
- `created_at`, `updated_at`

#### TagPreset
- `id`: Primary key
- `user_id`: Foreign key (nullable for system presets)
- `name`: Preset name
- `voice_type`: Voice configuration
- `effects`: JSON effects configuration
- `is_public`: Boolean for sharing
- `created_at`, `updated_at`

## Audio Processing Strategy

### Text-to-Speech Integration ✅ IMPLEMENTED

**Implemented Services:**

1. **OpenAI TTS** - Primary service
   - 6 voices: alloy, echo, fable, onyx, nova, shimmer
   - 2 models: tts-1 (faster), tts-1-hd (higher quality)
   - Pricing: $15 per 1M characters
   - Speed control: 0.25x to 4.0x
   - Formats: MP3, Opus, AAC, FLAC, WAV, PCM

2. **ElevenLabs** - Premium service
   - 100+ pre-made voices
   - Voice cloning capability
   - Emotion and style control
   - Pricing: ~$0.30 per 1000 characters
   - Free tier: 10,000 characters/month

**Implementation:**
```php
// app/Contracts/TextToSpeechService.php
interface TextToSpeechService
{
    public function generate(string $text, array $options = []): string;
    public function getAvailableVoices(): array;
    public function getVoicePreview(string $voiceId): ?string;
    public function estimateCost(string $text): float;
    public function validateCredentials(string $apiKey): bool;
}

// app/Services/TTS/OpenAiTtsService.php
class OpenAiTtsService implements TextToSpeechService { }

// app/Services/TTS/ElevenLabsTtsService.php
class ElevenLabsTtsService implements TextToSpeechService { }

// app/Services/TTS/TtsServiceFactory.php
class TtsServiceFactory
{
    public function make(?string $service = null, ?string $apiKey = null): TextToSpeechService;
}
```

### Audio Effects Processing ✅ IMPLEMENTED

**FFmpeg Integration:**
- Package: `php-ffmpeg/php-ffmpeg`
- Binary: Installed in Docker container
- Interface: `app/Contracts/AudioProcessor.php`
- Implementation: `app/Services/Audio/FfmpegAudioProcessor.php`

**Available Effects:**
- **Pitch Shifting**: -12 to +12 semitones
- **Speed Control**: 0.5x to 2.0x
- **Reverb**: Small room, large hall, stadium
- **Bass Boost**: Enhanced low frequencies
- **Normalization**: Loudness normalization (EBU R128)
- **Silence Trimming**: Remove silence from start/end

**Implementation:**
```php
// app/Contracts/AudioProcessor.php
interface AudioProcessor
{
    public function applyEffects(string $inputPath, array $effects): string;
    public function getDuration(string $path): float;
    public function convert(string $inputPath, string $format, array $options = []): string;
    public function normalize(string $inputPath): string;
    public function trimSilence(string $inputPath): string;
}

// app/Services/Audio/FfmpegAudioProcessor.php
class FfmpegAudioProcessor implements AudioProcessor { }
```

### Processing Flow
1. User submits tag request → Validation
2. Job dispatched to Redis queue → TagGenerationJob
3. TTS API call → Generate base audio
4. Apply effects via FFmpeg → Process audio
5. Store file in MinIO/R2/local → Update database
6. Notify user → WebSocket/polling

## Frontend Architecture

### Page Structure
```
resources/js/Pages/
├── Auth/              # Authentication pages
├── Dashboard.vue      # User dashboard
├── Tags/
│   ├── Index.vue      # List all tags
│   ├── Create.vue     # Create new tag
│   └── Show.vue       # View/download tag
└── Presets/
    ├── Index.vue      # List presets
    └── Create.vue     # Create preset
```

### Component Organization
```
resources/js/Components/
├── UI/                # Shadcn-style components
│   ├── Button.vue
│   ├── Input.vue
│   ├── Select.vue
│   └── Card.vue
├── Tag/
│   ├── AudioPlayer.vue
│   ├── VoiceSelector.vue
│   ├── EffectsPanel.vue
│   └── TagCard.vue
└── Layout/
    ├── AppLayout.vue
    └── GuestLayout.vue
```

### Type Safety with Wayfinder
- Import controller actions: `import { store } from '@/actions/App/Http/Controllers/TagController'`
- Type-safe route parameters
- Automatic form action/method generation

## API Integration Points ✅ IMPLEMENTED

### TTS Service Interface
```php
// app/Contracts/TextToSpeechService.php
interface TextToSpeechService
{
    public function generate(string $text, array $options = []): string;
    public function getAvailableVoices(): array;
    public function getVoicePreview(string $voiceId): ?string;
    public function estimateCost(string $text): float;
    public function validateCredentials(string $apiKey): bool;
}
```

**Implementations:**
- `app/Services/TTS/OpenAiTtsService.php`
- `app/Services/TTS/ElevenLabsTtsService.php`

### Audio Processing Interface
```php
// app/Contracts/AudioProcessor.php
interface AudioProcessor
{
    public function applyEffects(string $inputPath, array $effects): string;
    public function getDuration(string $path): float;
    public function convert(string $inputPath, string $format, array $options = []): string;
    public function normalize(string $inputPath): string;
    public function trimSilence(string $inputPath): string;
}
```

**Implementation:**
- `app/Services/Audio/FfmpegAudioProcessor.php`

### Storage Configuration ✅ IMPLEMENTED

**Available Disks:**
- `local`: Laravel's default local storage
- `minio`: S3-compatible storage for local development
- `r2`: Cloudflare R2 for production
- `s3`: AWS S3 (if needed)

**Configuration:**
- `config/filesystems.php`: Disk configurations
- `config/services.php`: Service credentials
- `config/audio.php`: Audio processing settings

## Queue Configuration

### Jobs
- `TagGenerationJob`: Main audio generation job
- `ApplyEffectsJob`: Audio effects processing
- `CleanupOldTagsJob`: Scheduled cleanup

### Queue Workers
- Development: `vendor/bin/sail artisan queue:listen`
- Production: Supervisor with multiple workers

## Testing Strategy

### Feature Tests
- Tag creation flow
- Audio generation process
- User authentication
- Tag listing and filtering

### Unit Tests
- Audio processing logic
- TTS service integration
- Effect application
- Validation rules

### Browser Tests (Pest 4)
- Complete user journey
- Tag creation UI
- Audio playback
- Download functionality

## Security Considerations

### Authentication
- Laravel Fortify for auth
- Rate limiting on tag generation
- User-specific tag access

### File Storage
- Private storage for user tags
- Signed URLs for downloads
- Automatic cleanup of old files

### API Security
- API key management for TTS services
- Request validation
- CSRF protection (Inertia handles this)

## Performance Optimization

### Caching Strategy
- Cache TTS voice lists
- Cache user presets
- Cache processed audio metadata

### Queue Optimization
- Async audio processing
- Job batching for bulk operations
- Failed job retry logic

### Frontend Optimization
- Lazy loading components
- Code splitting with Vite
- Asset optimization

## Development Workflow

### Local Development
1. Start Sail: `vendor/bin/sail up -d`
2. Run dev server: `vendor/bin/sail composer run dev`
3. Access at: `http://localhost`

### Code Quality
- Run Pint before commits: `vendor/bin/sail bin pint`
- Run tests: `vendor/bin/sail artisan test`
- Lint frontend: `vendor/bin/sail npm run lint`

### Database Management
- Migrations: `vendor/bin/sail artisan migrate`
- Seeders: `vendor/bin/sail artisan db:seed`
- Fresh database: `vendor/bin/sail artisan migrate:fresh --seed`

## Deployment Considerations

### Environment Requirements
- PHP 8.2+
- MySQL/PostgreSQL
- Redis (for queues and cache)
- FFmpeg installed
- Sufficient storage for audio files

### Configuration
- TTS API credentials
- Queue worker configuration
- Storage disk configuration
- CDN for audio delivery (optional)

## Future Enhancements

### Phase 2 Features
- Social sharing of tags
- Tag marketplace
- Collaborative presets
- Advanced audio editing
- Mobile app (via Capacitor)

### Technical Improvements
- WebSocket for real-time updates
- CDN integration
- Advanced caching
- Microservices for audio processing
- AI-powered voice cloning

## Resources

### Documentation
- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Inertia.js Docs](https://inertiajs.com)
- [Vue 3 Docs](https://vuejs.org)
- [TailwindCSS 4 Docs](https://tailwindcss.com)
- [Pest Docs](https://pestphp.com)

### Tools
- Laravel Boost MCP Server
- Laravel Wayfinder
- Shadcn Vue Components
