<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import ServiceSelector from '@/components/dj-tags/ServiceSelector.vue';
import VoicePicker from '@/components/dj-tags/VoicePicker.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, create, store } from '@/routes/dj-tags';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';

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
        <div class="max-w-4xl mx-auto p-4 w-full sm:p-6 lg:p-8">
            <h1 class="text-2xl font-bold mb-6">Create New DJ Tag</h1>

            <form @submit.prevent="submit" class="space-y-8 bg-card p-6 rounded-lg shadow border border-border">
                <!-- Service Selector -->
                <div>
                    <h3 class="text-lg font-medium mb-3 ">1. Select Service</h3>
                    <ServiceSelector v-model="form.service" />
                    <div v-if="form.errors.service" class="text-destructive text-sm mt-1">{{ form.errors.service }}</div>
                </div>

                <!-- Text Input -->
                <div>
                    <h3 class="text-lg font-medium mb-3 ">2. Script</h3>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-muted-foreground">Text to Speak</label>
                        <Textarea
                            class="min-h-24"
                            placeholder="Type your DJ drop text here..."
                            v-model="form.text"
                        />
                        <div v-if="form.errors.text" class="text-destructive text-sm">{{ form.errors.text }}</div>
                        <div class="text-xs text-muted-foreground text-right">
                            {{ form.text.length }} / {{ 500 }} characters
                        </div>
                    </div>
                </div>

                <!-- Voice Selection -->
                <div>
                    <h3 class="text-lg font-medium mb-3 ">3. Choose Voice</h3>
                    <VoicePicker
                        v-model="form.voice_id"
                        :service="form.service"
                    />
                    <div v-if="form.errors.voice_id" class="text-destructive text-sm mt-1">{{ form.errors.voice_id }}</div>
                </div>

                <!-- Settings & Effects -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Voice Settings -->
                    <div class="space-y-4">
                        <h4 class="font-medium  border-b border-border pb-2">Voice Settings</h4>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground">Speed (x{{ form.voice_settings.speed }})</label>
                            <input
                                type="range"
                                v-model.number="form.voice_settings.speed"
                                min="0.25" max="4.0" step="0.25"
                                class="w-full accent-primary"
                            />
                        </div>

                         <div class="grid gap-2" v-if="form.service === 'elevenlabs'">
                            <label class="text-sm text-muted-foreground">Stability ({{ Math.round(form.voice_settings.stability * 100) }}%)</label>
                            <input
                                type="range"
                                v-model.number="form.voice_settings.stability"
                                min="0" max="1" step="0.05"
                                class="w-full accent-primary"
                            />
                        </div>
                    </div>

                    <!-- Audio Effects -->
                    <div class="space-y-4">
                        <h4 class="font-medium  border-b border-border pb-2">Post-Processing</h4>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground">Pitch Shift ({{ form.audio_effects.pitch > 0 ? '+' : '' }}{{ form.audio_effects.pitch }} semitones)</label>
                            <input
                                type="range"
                                v-model.number="form.audio_effects.pitch"
                                min="-12" max="12" step="1"
                                class="w-full h-2 bg-muted rounded-lg appearance-none cursor-pointer accent-primary"
                            />
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground">Speed ({{ form.audio_effects.speed }}x)</label>
                            <input
                                type="range"
                                v-model.number="form.audio_effects.speed"
                                min="0.5" max="2.0" step="0.1"
                                class="w-full h-2 bg-muted rounded-lg appearance-none cursor-pointer accent-primary"
                            />
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm text-muted-foreground">Reverb</label>
                            <Select v-model="form.audio_effects.reverb">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Reverb" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem :value="null">None</SelectItem>
                                        <SelectItem value="small_room">Small Room</SelectItem>
                                        <SelectItem value="large_hall">Large Hall</SelectItem>
                                        <SelectItem value="stadium">Stadium</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>

                         <div class="flex items-center justify-between">
                            <label
                                class="cursor-pointer flex-1 text-sm text-muted-foreground select-none"
                                for="bass_boost"
                            >
                                Bass Boost
                            </label>
                            <Checkbox id="bass_boost" class="cursor-pointer" v-model:checked="form.audio_effects.bass_boost" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label
                                class="cursor-pointer flex-1 text-sm text-muted-foreground select-none"
                                for="normalize"
                            >
                                Normalize
                            </label>
                            <Checkbox id="normalize" class="cursor-pointer" v-model:checked="form.audio_effects.normalize" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border">
                    <Button
                        type="submit"
                        :disabled="form.processing"
                    >
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <span v-if="form.processing">Generating...</span>
                        <span v-else>Generate Tag</span>
                    </Button>
                </div>

                <div v-if="form.errors.rate_limit" class="p-3 bg-destructive/10 text-destructive rounded-md text-sm">
                    {{ form.errors.rate_limit }}
                </div>
            </form>
        </div>
    </AppLayout>
</template>
