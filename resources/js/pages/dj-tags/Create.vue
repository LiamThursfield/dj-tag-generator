<script lang="ts" setup>
import AudioEffectsSelector from '@/components/dj-tags/AudioEffectsSelector.vue';
import ServiceSelector from '@/components/dj-tags/ServiceSelector.vue';
import VoicePicker from '@/components/dj-tags/VoicePicker.vue';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { create, index, store } from '@/routes/dj-tags';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

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
        reverb: 'none',
        bass_boost: false,
        tremolo: false,
        echo: false,
        chorus: false,
        lofi_telephone: false,
        bitcrush: false,
        normalize: false,
    },
    format: 'mp3',
    // this isn't accepted on the backend, but allows us to use the error
    // without a TypeScript complaint
    rate_limit: null,
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
            <h1 class="mb-6 text-xl font-bold">Create New DJ Tag</h1>

            <form
                @submit.prevent="submit"
                class="space-y-8 rounded-lg border border-border bg-card p-6 shadow"
            >
                <!-- Service Selector -->
                <div>
                    <label for="service" class="block font-medium">
                        Service
                    </label>
                    <ServiceSelector v-model="form.service" class="mt-1" />
                    <div
                        v-if="form.errors.service"
                        class="mt-1 text-sm text-destructive"
                    >
                        {{ form.errors.service }}
                    </div>
                </div>

                <!-- Script -->
                <div>
                    <div class="space-y-2">
                        <label for="text" class="block font-medium">
                            Tag Script
                        </label>
                        <Textarea
                            v-model="form.text"
                            class="min-h-18"
                            placeholder="Type your DJ drop text here..."
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
                    <label for="text" class="block font-medium"> Voice </label>

                    <VoicePicker
                        class="mt-1"
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
                                v-model.number="form.voice_settings.speed"
                                class="w-full accent-primary"
                                max="4.0"
                                min="0.25"
                                step="0.25"
                                type="range"
                            />
                        </div>

                        <div
                            v-if="form.service === 'elevenlabs'"
                            class="grid gap-2"
                        >
                            <label class="text-sm text-muted-foreground"
                                >Stability ({{
                                    Math.round(
                                        form.voice_settings.stability * 100,
                                    )
                                }}%)</label
                            >
                            <input
                                v-model.number="form.voice_settings.stability"
                                class="w-full accent-primary"
                                max="1"
                                min="0"
                                step="0.05"
                                type="range"
                            />
                        </div>
                    </div>

                    <!-- Audio Effects -->
                    <div class="space-y-4">
                        <h4 class="border-b border-border pb-2 font-medium">
                            Post-Processing
                        </h4>

                        <AudioEffectsSelector v-model="form.audio_effects" />
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex items-center justify-end gap-4 border-t border-border pt-4"
                >
                    <Button :disabled="form.processing" type="submit">
                        <template v-if="form.processing">
                            <LoaderCircle
                                class="h-5 w-5 animate-spin text-primary-foreground"
                            />

                            <span>Generating...</span>
                        </template>

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
