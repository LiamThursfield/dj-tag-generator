<script setup lang="ts">
import ServiceSelector from '@/components/dj-tags/ServiceSelector.vue';
import VoicePicker from '@/components/dj-tags/VoicePicker.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { create, index, store } from '@/routes/dj-tags';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    preferred_service: string;
    presets: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard.url() },
    { title: 'DJ Tags', href: index.url() },
    { title: 'New Tag', href: create.url() },
];

const form = useForm({
    text: '',
    service: props.preferred_service || 'elevenlabs',
    voice_id: '',
    voice_settings: {
        speed: 1.0,
        stability: 0.5,
    },
    audio_effects: {
        pitch: 0,
        speed: 1.0,
        reverb: null,
        bass_boost: false,
        normalize: true,
    },
    format: 'mp3',
});

const submit = () => {
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirect handles by controller
        },
    });
};
</script>

<template>
    <Head title="Create DJ Tag" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-4xl p-4 sm:p-6 lg:p-8">
            <h1 class="mb-6 text-2xl font-bold">Create New DJ Tag</h1>

            <form
                @submit.prevent="submit"
                class="space-y-8 rounded-lg border border-border bg-card p-6 shadow"
            >
                <!-- Service Selector -->
                <div>
                    <h3 class="mb-3 text-lg font-medium">1. Select Service</h3>
                    <ServiceSelector v-model="form.service" />
                    <div
                        v-if="form.errors.service"
                        class="mt-1 text-sm text-destructive"
                    >
                        {{ form.errors.service }}
                    </div>
                </div>

                <!-- Text Input -->
                <div>
                    <h3 class="mb-3 text-lg font-medium">2. Script</h3>
                    <div class="space-y-2">
                        <label
                            class="block text-sm font-medium text-muted-foreground"
                            >Text to Speak</label
                        >
                        <Textarea
                            class="min-h-24"
                            placeholder="Type your DJ drop text here..."
                            v-model="form.text"
                        />
                        <div
                            v-if="form.errors.text"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.text }}
                        </div>
                        <div class="text-right text-xs text-muted-foreground">
                            {{ form.text.length }} / {{ 500 }} characters
                        </div>
                    </div>
                </div>

                <!-- Voice Selection -->
                <div>
                    <h3 class="mb-3 text-lg font-medium">3. Choose Voice</h3>
                    <VoicePicker
                        v-model="form.voice_id"
                        :service="form.service"
                    />
                    <div
                        v-if="form.errors.voice_id"
                        class="mt-1 text-sm text-destructive"
                    >
                        {{ form.errors.voice_id }}
                    </div>
                </div>

                <!-- Settings & Effects -->
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Voice Settings -->
                    <div class="space-y-4">
                        <h4 class="border-b border-border pb-2 font-medium">
                            Voice Settings
                        </h4>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground"
                                >Speed (x{{ form.voice_settings.speed }})</label
                            >
                            <input
                                type="range"
                                v-model.number="form.voice_settings.speed"
                                min="0.25"
                                max="4.0"
                                step="0.25"
                                class="w-full accent-primary"
                            />
                        </div>

                        <div
                            class="grid gap-2"
                            v-if="form.service === 'elevenlabs'"
                        >
                            <label class="text-sm text-muted-foreground"
                                >Stability ({{
                                    Math.round(
                                        form.voice_settings.stability * 100,
                                    )
                                }}%)</label
                            >
                            <input
                                type="range"
                                v-model.number="form.voice_settings.stability"
                                min="0"
                                max="1"
                                step="0.05"
                                class="w-full accent-primary"
                            />
                        </div>
                    </div>

                    <!-- Audio Effects -->
                    <div class="space-y-4">
                        <h4 class="border-b border-border pb-2 font-medium">
                            Post-Processing
                        </h4>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground"
                                >Pitch Shift ({{
                                    form.audio_effects.pitch > 0 ? '+' : ''
                                }}{{
                                    form.audio_effects.pitch
                                }}
                                semitones)</label
                            >
                            <input
                                type="range"
                                v-model.number="form.audio_effects.pitch"
                                min="-12"
                                max="12"
                                step="1"
                                class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-muted accent-primary"
                            />
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground"
                                >Speed ({{ form.audio_effects.speed }}x)</label
                            >
                            <input
                                type="range"
                                v-model.number="form.audio_effects.speed"
                                min="0.5"
                                max="2.0"
                                step="0.1"
                                class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-muted accent-primary"
                            />
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground"
                                >Reverb</label
                            >
                            <Select v-model="form.audio_effects.reverb">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Reverb" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem :value="null"
                                            >None</SelectItem
                                        >
                                        <SelectItem value="small_room"
                                            >Small Room</SelectItem
                                        >
                                        <SelectItem value="large_hall"
                                            >Large Hall</SelectItem
                                        >
                                        <SelectItem value="stadium"
                                            >Stadium</SelectItem
                                        >
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="flex items-center justify-between">
                            <label
                                class="flex-1 cursor-pointer text-sm text-muted-foreground select-none"
                                for="bass_boost"
                            >
                                Bass Boost
                            </label>
                            <Checkbox
                                id="bass_boost"
                                class="cursor-pointer"
                                v-model:checked="form.audio_effects.bass_boost"
                            />
                        </div>

                        <div class="flex items-center justify-between">
                            <label
                                class="flex-1 cursor-pointer text-sm text-muted-foreground select-none"
                                for="normalize"
                            >
                                Normalize
                            </label>
                            <Checkbox
                                id="normalize"
                                class="cursor-pointer"
                                v-model:checked="form.audio_effects.normalize"
                            />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex items-center justify-end gap-4 border-t border-border pt-4"
                >
                    <Button type="submit" :disabled="form.processing">
                        <svg
                            v-if="form.processing"
                            class="mr-3 -ml-1 h-5 w-5 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>

                        <span v-if="form.processing">Generating...</span>
                        <span v-else>Generate Tag</span>
                    </Button>
                </div>

                <div
                    v-if="form.errors.rate_limit"
                    class="rounded-md bg-destructive/10 p-3 text-sm text-destructive"
                >
                    {{ form.errors.rate_limit }}
                </div>
            </form>
        </div>
    </AppLayout>
</template>
