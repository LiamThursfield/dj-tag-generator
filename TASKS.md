# DJ Tag Generator - Development Task List

> **Last Updated**: 2026-01-06  
> **Status**: Phase 1 - Database & Models (In Progress)

This task list tracks all development work for the DJ Tag Generator application. Please keep this updated as you complete tasks or add new ones.

---

## ✅ Completed

### Infrastructure & Setup
- [x] Project initialization with Laravel 12, Vue 3, Inertia.js
- [x] Docker configuration (Sail with FFmpeg)
- [x] Redis queue and cache setup
- [x] MinIO S3-compatible storage setup
- [x] Cloudflare R2 storage configuration

### Service Layer
- [x] TTS service abstraction layer (`TextToSpeechService` interface)
- [x] OpenAI TTS service implementation
- [x] ElevenLabs TTS service implementation
- [x] TTS service factory
- [x] Audio processor interface (`AudioProcessor`)
- [x] FFmpeg audio processor implementation
- [x] Audio configuration with effects (reverb, pitch, speed, bass boost, normalize)

### Security & Cost Controls
- [x] PHP resource limits (5MB upload, 256MB memory, 60s timeout)
- [x] Text length limits (500 characters)
- [x] Rate limiting configuration (10/hour, 50/day)
- [x] FFmpeg resource limits (2 threads, 60s timeout)
- [x] Security documentation (SECURITY.md)
- [x] Cost control documentation

### Documentation
- [x] README.md with installation and usage
- [x] PROJECT.md with technical architecture
- [x] SECURITY.md with security guidelines
- [x] .cursorrules for AI assistance
- [x] CONTRIBUTING.md for developers

---

## ✅ Completed

### Phase 1: Database & Models
- [x] Create `dj_tags` table migration
- [x] Create `dj_tag_presets` table migration
- [x] Update `users` table with TTS API key fields (openai_api_key, elevenlabs_api_key)
- [x] Create `DjTag` model with relationships
- [x] Create `DjTagPreset` model with relationships
- [x] Update `User` model with encrypted API key casting (all API keys encrypted)
- [x] Create `DjTagFactory` for testing
- [x] Create `DjTagPresetFactory` for testing
- [x] Create database seeders

---

## ✅ Completed

### Phase 2: Backend Logic

#### User Plans & Limits Refactor
- [x] Create `Plan` model & migration
- [x] Remove legacy limits migration
- [x] Add `plan_id` to users table
- [x] Seed default plans (Free/Premium)
- [x] Update `User` model relationships & helpers
- [x] Refactor limit checks in controllers
- [x] Update tests
- [x] Refine User Plans (Overrides & Defaults)


#### Queue Jobs
- [x] Create `GenerateDjTagJob` (TTS → Raw Storage → Version 1)
- [x] Create `ReprocessDjTagJob` (Raw Storage → New Version)
- [x] Implement error handling and retry logic
- [ ] Create `CleanupOldDjTagsJob` for automatic file cleanup (raw + versions)
- [ ] Add job monitoring and logging

#### Action Classes
- [x] Create `GenerateDjTag` action (Master + Job Dispatch)
- [x] Create `ReprocessDjTag` logic (Handle via Job)

#### Controllers
- [x] Create `DjTagController` (index, store, show, reprocess)
- [x] Create `Api/TtsProviderController`
- [x] Create `VoiceController`
- [x] Add reprocess route
- [ ] Add Wayfinder route generation

#### Form Requests
- [x] Create `StoreDjTagRequest` (Validation & Rate Limiting)
- [ ] Create `UpdateDjTagRequest`
- [ ] Create `StoreDjTagPresetRequest`
- [ ] Create `UpdateSettingsRequest` with API key validation

#### Middleware
- [ ] Create `CheckRateLimit` middleware
- [ ] Create `ValidateApiKey` middleware
- [ ] Create `TrackUsage` middleware (cost tracking)

#### API Resources
- [ ] Create `DjTagResource` for API responses
- [ ] Create `DjTagPresetResource`
- [ ] Create `VoiceResource`
- [ ] Create `DjTagCollection` with pagination

---

### Phase 3: Frontend UI

#### Layouts
- [ ] Create `AppLayout.vue` (authenticated users)
- [ ] Create `GuestLayout.vue` (login/register)
- [ ] Add navigation menu
- [ ] Add breadcrumbs
- [ ] Add notifications/toast system

#### Components - DJ Tag Creation
- [ ] Create `DjTagForm.vue` (main creation form)
- [ ] Create `TextInput.vue` (with character counter)
- [ ] Create `VoiceSelector.vue` (dropdown with voice previews)
- [ ] Create `EffectsPanel.vue` (pitch, speed, reverb, etc.)
- [ ] Create `PresetSelector.vue` (load saved presets)
- [ ] Create `CostEstimator.vue` (show estimated cost)

#### Components - Audio Playback
- [ ] Create `AudioPlayer.vue` (play/pause, seek)
- [ ] Create `WaveformVisualizer.vue` (audio waveform)
- [ ] Create `DownloadButton.vue` (with format options)

#### Components - DJ Tag Library
- [ ] Create `DjTagCard.vue` (display tag info)
- [ ] Create `DjTagGrid.vue` (grid layout)
- [ ] Create `DjTagFilters.vue` (filter by date, voice, etc.)
- [ ] Create `DjTagSearch.vue` (search tags)

#### Components - Settings
- [ ] Create `ApiKeyInput.vue` (secure input with validation, reusable for both services)
- [ ] Create `ServiceSelector.vue` (choose OpenAI, ElevenLabs, or both)
- [ ] Create `UsageStats.vue` (show usage and costs per service)
- [ ] Create `ServiceStatusBadge.vue` (show which services are configured)

#### Inertia Pages
- [ ] Create `Dashboard.vue` (recent tags, quick create)
- [ ] Create `DjTags/Index.vue` (tag library)
- [ ] Create `DjTags/Create.vue` (create new tag)
- [ ] Create `DjTags/Show.vue` (view/download tag)
- [ ] Create `DjTagPresets/Index.vue` (manage presets)
- [ ] Create `Settings/Index.vue` (API keys, preferences)

#### TypeScript Types
- [ ] Generate types from Laravel resources
- [ ] Create `DjTag` type
- [ ] Create `DjTagPreset` type
- [ ] Create `Voice` type
- [ ] Create `AudioEffect` type

---

### Phase 4: Testing

#### Feature Tests
- [ ] Test tag creation flow (TTS → FFmpeg → Storage)
- [ ] Test rate limiting enforcement
- [ ] Test API key validation
- [ ] Test preset creation and usage
- [ ] Test file cleanup job
- [ ] Test error handling (invalid API key, TTS failure, etc.)

#### Unit Tests
- [ ] Test `OpenAiTtsService` (mocked API)
- [ ] Test `ElevenLabsTtsService` (mocked API)
- [ ] Test `FfmpegAudioProcessor` effects
- [ ] Test cost estimation accuracy
- [ ] Test rate limiting logic
- [ ] Test input validation

#### Browser Tests (Pest 4)
- [ ] Test complete tag creation flow in browser
- [ ] Test audio playback
- [ ] Test preset management
- [ ] Test settings page
- [ ] Test error states and validation

---

### Phase 5: Polish & Features

#### User Experience
- [ ] Add loading states and progress indicators
- [ ] Add error messages and validation feedback
- [ ] Add success notifications
- [ ] Add keyboard shortcuts
- [ ] Add dark mode support (if not already)

#### Advanced Features
- [ ] Batch tag generation (multiple tags at once)
- [x] Tag versioning (regenerate with different settings)
- [ ] Tag sharing (public links)
- [ ] Tag templates (common phrases)
- [ ] Voice comparison (A/B test voices)

#### Analytics & Monitoring
- [ ] Add usage tracking (tags created, costs)
- [ ] Add error tracking (Sentry integration)
- [ ] Add performance monitoring
- [ ] Create admin dashboard (user stats, costs)

#### Documentation
- [ ] User guide (how to get API keys)
- [ ] Video tutorial (tag creation walkthrough)
- [ ] API documentation (if exposing API)
- [ ] Deployment guide (production setup)

---

### Phase 6: Deployment

#### Production Setup
- [ ] Configure production environment variables
- [ ] Set up production database (MySQL/PostgreSQL)
- [ ] Configure Redis for production
- [ ] Set up Cloudflare R2 bucket
- [ ] Configure queue workers (Supervisor)
- [ ] Set up SSL certificates
- [ ] Configure CDN for audio files

#### CI/CD
- [ ] Set up GitHub Actions for testing
- [ ] Set up automated deployments
- [ ] Configure database backups
- [ ] Set up monitoring and alerts

#### Performance
- [ ] Optimize database queries
- [ ] Add caching where appropriate
- [ ] Optimize asset loading
- [ ] Configure CDN for static assets

---

## 🐛 Known Issues

_No known issues yet - add them here as they're discovered_

---

## 💡 Future Ideas

### Potential Features (Not Prioritized)
- [ ] Mobile app (React Native or Capacitor)
- [ ] Voice cloning (custom voices)
- [ ] AI-powered tag suggestions
- [ ] Multi-language support
- [ ] Collaboration features (team accounts)
- [ ] Integration with DJ software (Serato, Rekordbox)
- [ ] Marketplace for presets
- [ ] Advanced audio editing (trim, fade, etc.)
- [ ] Real-time collaboration
- [ ] WebSocket for live progress updates

---

## 📝 Notes for Contributors

### When Working on Tasks:
1. **Update this file** when starting a task (move to "In Progress")
2. **Check off completed tasks** with `[x]`
3. **Add new tasks** as they're discovered
4. **Update "Last Updated"** date at the top
5. **Add issues** to "Known Issues" section
6. **Document decisions** in relevant sections

### Task Priorities:
- **Phase 1** (Database) must be completed first
- **Phase 2** (Backend) depends on Phase 1
- **Phase 3** (Frontend) can start once Phase 2 controllers are done
- **Phase 4** (Testing) should happen alongside development
- **Phase 5** (Polish) after core features work
- **Phase 6** (Deployment) when ready for production

### Conventions:
- Use `[ ]` for incomplete tasks
- Use `[x]` for completed tasks
- Use `[~]` for partially complete tasks
- Add dates in YYYY-MM-DD format
- Link to relevant files when helpful

---

## 🎯 Current Sprint Focus

**Goal**: Complete Phase 1 - Database & Models

**Target Date**: TBD

**Tasks This Sprint**:
1. Create all database migrations
2. Create all models with relationships
3. Create factories and seeders
4. Test the full pipeline (TTS → FFmpeg → Storage)

---

## 📊 Progress Tracking

- **Infrastructure**: ✅ 100% Complete
- **Service Layer**: ✅ 100% Complete
- **Security**: ✅ 100% Complete
- **Documentation**: ✅ 100% Complete
- **Phase 1 (Database)**: 🚧 0% Complete
- **Phase 2 (Backend)**: ⏳ 0% Not Started
- **Phase 3 (Frontend)**: ⏳ 0% Not Started
- **Phase 4 (Testing)**: ⏳ 0% Not Started
- **Phase 5 (Polish)**: ⏳ 0% Not Started
- **Phase 6 (Deployment)**: ⏳ 0% Not Started

**Overall Progress**: ~55% (Infrastructure, Foundation, Database Complete)

---

_This task list is a living document. Keep it updated and use it to coordinate work across team members and AI agents._
