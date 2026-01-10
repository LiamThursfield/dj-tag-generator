<?php

namespace App\Services\Audio;

use App\Contracts\AudioProcessor;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Wav;
use Illuminate\Support\Str;

class FfmpegAudioProcessor implements AudioProcessor
{
    protected FFMpeg $ffmpeg;

    public function __construct()
    {
        $this->ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => config('audio.ffmpeg.binary', 'ffmpeg'),
            'ffprobe.binaries' => config('audio.ffmpeg.ffprobe', 'ffprobe'),
            'timeout' => config('audio.ffmpeg.timeout', 3600),
            'ffmpeg.threads' => config('audio.ffmpeg.threads', 12),
        ]);
    }

    public function applyEffects(string $inputPath, array $effects): string
    {
        $audio = $this->ffmpeg->open($inputPath);

        $filters = $this->buildFilterChain($effects);

        $outputPath = $this->generateOutputPath($inputPath);

        $format = $this->getAudioFormat(config('audio.output_format', 'mp3'));
        $format->setAudioKiloBitrate((int) str_replace('k', '', config('audio.bitrate', '192k')));

        if (! empty($filters)) {
            $audio->filters()
                ->custom(implode(',', $filters));
        }

        $audio->save($format, $outputPath);

        return $outputPath;
    }

    public function getDuration(string $path): float
    {
        $ffprobe = \FFMpeg\FFProbe::create([
            'ffprobe.binaries' => config('audio.ffmpeg.ffprobe', 'ffprobe'),
        ]);

        return $ffprobe->format($path)->get('duration');
    }

    public function convert(string $inputPath, string $format, array $options = []): string
    {
        $audio = $this->ffmpeg->open($inputPath);

        $outputPath = $this->generateOutputPath($inputPath, $format);

        $audioFormat = $this->getAudioFormat($format);

        if (isset($options['bitrate'])) {
            $audioFormat->setAudioKiloBitrate((int) str_replace('k', '', $options['bitrate']));
        }

        $audio->save($audioFormat, $outputPath);

        return $outputPath;
    }

    public function normalize(string $inputPath): string
    {
        $audio = $this->ffmpeg->open($inputPath);

        $outputPath = $this->generateOutputPath($inputPath);

        $format = $this->getAudioFormat(config('audio.output_format', 'mp3'));
        $format->setAudioKiloBitrate((int) str_replace('k', '', config('audio.bitrate', '192k')));

        $normalizeFilter = config('audio.effects.normalize.filter', 'loudnorm=I=-16:TP=-1.5:LRA=11');

        $audio->filters()
            ->custom($normalizeFilter);

        $audio->save($format, $outputPath);

        return $outputPath;
    }

    public function trimSilence(string $inputPath): string
    {
        $audio = $this->ffmpeg->open($inputPath);

        $outputPath = $this->generateOutputPath($inputPath);

        $format = $this->getAudioFormat(config('audio.output_format', 'mp3'));
        $format->setAudioKiloBitrate((int) str_replace('k', '', config('audio.bitrate', '192k')));

        // Remove silence from start and end
        $audio->filters()
            ->custom('silenceremove=start_periods=1:start_duration=0.1:start_threshold=-50dB:stop_periods=-1:stop_duration=0.5:stop_threshold=-50dB');

        $audio->save($format, $outputPath);

        return $outputPath;
    }

    protected function buildFilterChain(array $effects): array
    {
        $filters = [];

        // Pitch shift
        if (isset($effects['pitch'])) {
            $semitones = $effects['pitch'];
            $ratio = pow(2, $semitones / 12);
            $filters[] = "asetrate=44100*{$ratio},aresample=44100";
        }

        // Speed change
        if (isset($effects['speed'])) {
            $speed = $effects['speed'];
            $filters[] = "atempo={$speed}";
        }

        // Reverb
        if (isset($effects['reverb'])) {
            $reverbType = $effects['reverb'];
            $reverbConfig = config("audio.effects.reverb.{$reverbType}");

            if ($reverbConfig) {
                $filters[] = $reverbConfig['filter'];
            }
        }

        // Bass boost
        if (isset($effects['bass_boost']) && $effects['bass_boost']) {
            $filters[] = config('audio.effects.bass_boost.filter', 'bass=g=10:f=110:w=0.3');
        }

        // Normalize
        if (isset($effects['normalize']) && $effects['normalize']) {
            $filters[] = config('audio.effects.normalize.filter', 'loudnorm=I=-16:TP=-1.5:LRA=11');
        }

        return $filters;
    }

    protected function getAudioFormat(string $format)
    {
        return match ($format) {
            'mp3' => new Mp3,
            'wav' => new Wav,
            default => new Mp3,
        };
    }

    protected function generateOutputPath(string $inputPath, ?string $extension = null): string
    {
        $extension = $extension ?? pathinfo($inputPath, PATHINFO_EXTENSION);
        $filename = Str::uuid().'.'.$extension;

        $path = storage_path('app/processed/'.$filename);

        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        return $path;
    }
}
