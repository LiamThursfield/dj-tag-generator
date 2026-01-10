<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Audio Processing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for audio generation and processing, including output
    | formats, quality settings, and effect options.
    |
    */

    'output_format' => env('AUDIO_OUTPUT_FORMAT', 'mp3'),

    'sample_rate' => env('AUDIO_SAMPLE_RATE', 44100),

    'bitrate' => env('AUDIO_BITRATE', '192k'),

    'max_duration' => env('AUDIO_MAX_DURATION', 10), // seconds - prevents abuse

    'storage_disk' => env('AUDIO_STORAGE_DISK', 'public'),

    'cleanup_days' => env('AUDIO_CLEANUP_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Security & Cost Controls
    |--------------------------------------------------------------------------
    |
    | These limits prevent abuse and control costs for TTS API usage.
    |
    */

    'max_text_length' => env('AUDIO_MAX_TEXT_LENGTH', 500), // characters - typical DJ tag is 20-100 chars

    'max_file_size' => env('AUDIO_MAX_FILE_SIZE', 5242880), // 5MB in bytes

    'allowed_formats' => ['mp3', 'wav'], // Limit to common formats

    'rate_limiting' => [
        'enabled' => env('AUDIO_RATE_LIMITING_ENABLED', true),
        'max_per_hour' => env('AUDIO_MAX_PER_HOUR', 10), // Max tags per user per hour
        'max_per_day' => env('AUDIO_MAX_PER_DAY', 50), // Max tags per user per day
    ],

    /*
    |--------------------------------------------------------------------------
    | FFmpeg Configuration
    |--------------------------------------------------------------------------
    */

    'ffmpeg' => [
        'binary' => env('FFMPEG_BINARY', 'ffmpeg'),
        'ffprobe' => env('FFPROBE_BINARY', 'ffprobe'),
        'timeout' => 60, // Reduced from 3600 - 60 seconds is plenty for short audio
        'threads' => 2, // Reduced from 12 - DJ tags don't need many threads
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Effects
    |--------------------------------------------------------------------------
    */

    'effects' => [
        'reverb' => [
            'small_room' => [
                'name' => 'Small Room',
                'filter' => 'aecho=0.8:0.88:60:0.4',
            ],
            'large_hall' => [
                'name' => 'Large Hall',
                'filter' => 'aecho=0.8:0.9:1000:0.3',
            ],
            'stadium' => [
                'name' => 'Stadium',
                'filter' => 'aecho=0.8:0.9:1800:0.25',
            ],
        ],

        'pitch' => [
            'min' => -12, // semitones
            'max' => 12,
        ],

        'speed' => [
            'min' => 0.5,
            'max' => 2.0,
        ],

        'bass_boost' => [
            'filter' => 'bass=g=10:f=110:w=0.3',
        ],

        'normalize' => [
            'filter' => 'loudnorm=I=-16:TP=-1.5:LRA=11',
        ],
    ],

];
