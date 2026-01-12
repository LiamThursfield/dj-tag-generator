<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit, update } from '@/routes/settings/api-services';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    api_keys: {
        openai: string;
        elevenlabs: string;
    };
    preferred_tts_service: string;
    status?: string;
}>();

const form = useForm({
    openai_api_key: props.api_keys.openai,
    elevenlabs_api_key: props.api_keys.elevenlabs,
    preferred_tts_service: props.preferred_tts_service,
});

const elevenlabsApiKeyUrl = 'https://elevenlabs.io/app/settings/api-keys';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'API Services',
        href: edit().url,
    },
];

const submit = () => {
    form.patch(update.url(), {
        preserveScroll: true,
        onSuccess: () => {
            // Success handling
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="API Services" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="API Services"
                    description="Manage your third-party TTS service credentials. Your keys are stored encrypted for security."
                />

                <div class="space-y-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Preferred Service -->
                        <div class="space-y-4">
                            <Label>Preferred TTS Service</Label>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none dark:bg-zinc-900"
                                    :class="
                                        form.preferred_tts_service ===
                                        'elevenlabs'
                                            ? 'border-primary ring-1 ring-primary'
                                            : 'border-muted'
                                    "
                                >
                                    <input
                                        type="radio"
                                        value="elevenlabs"
                                        v-model="form.preferred_tts_service"
                                        class="sr-only"
                                    />
                                    <div class="flex flex-1">
                                        <div class="flex flex-col">
                                            <span
                                                class="block text-sm font-medium text-primary"
                                                >ElevenLabs</span
                                            >
                                            <span
                                                class="mt-1 flex items-center text-sm text-muted-foreground"
                                                >Premium Quality</span
                                            >
                                        </div>
                                    </div>
                                    <div
                                        v-if="
                                            form.preferred_tts_service ===
                                            'elevenlabs'
                                        "
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-primary"
                                    >
                                        <svg
                                            class="h-3 w-3 text-primary-foreground"
                                            fill="currentColor"
                                            viewBox="0 0 12 12"
                                        >
                                            <path
                                                d="M3.707 5.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L5 6.586 3.707 5.293z"
                                            />
                                        </svg>
                                    </div>
                                </label>

                                <label
                                    class="relative flex cursor-not-allowed rounded-lg border bg-white p-4 opacity-50 shadow-sm focus:outline-none dark:bg-zinc-900"
                                    :class="
                                        form.preferred_tts_service === 'openai'
                                            ? 'border-primary ring-1 ring-primary'
                                            : 'border-muted'
                                    "
                                >
                                    <input
                                        type="radio"
                                        disabled
                                        value="openai"
                                        v-model="form.preferred_tts_service"
                                        class="sr-only"
                                    />
                                    <div class="flex flex-1">
                                        <div class="flex flex-col">
                                            <span
                                                class="block text-sm font-medium text-primary"
                                                >OpenAI (Coming Soon)</span
                                            >
                                            <span
                                                class="mt-1 flex items-center text-sm text-muted-foreground"
                                                >Fast & Efficient</span
                                            >
                                        </div>
                                    </div>
                                    <div
                                        v-if="
                                            form.preferred_tts_service ===
                                            'openai'
                                        "
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-primary"
                                    >
                                        <svg
                                            class="h-3 w-3 text-primary-foreground"
                                            fill="currentColor"
                                            viewBox="0 0 12 12"
                                        >
                                            <path
                                                d="M3.707 5.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L5 6.586 3.707 5.293z"
                                            />
                                        </svg>
                                    </div>
                                </label>
                            </div>
                            <InputError
                                :message="form.errors.preferred_tts_service"
                            />
                        </div>

                        <div
                            class="space-y-4 border-t pt-4 border-muted"
                        >
                            <!-- ElevenLabs API Key -->
                            <div class="space-y-2 pt-2">
                                <Label for="elevenlabs_api_key"
                                    >ElevenLabs API Key</Label
                                >
                                <Input
                                    id="elevenlabs_api_key"
                                    v-model="form.elevenlabs_api_key"
                                    type="password"
                                    autocomplete="off"
                                    placeholder="ElevenLabs API Key"
                                />
                                <p class="text-xs text-muted-foreground">
                                    Required for ElevenLabs premium voices. To
                                    get an API Key
                                    <a
                                        class="text-primary hover:underline"
                                        :href="elevenlabsApiKeyUrl"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        click here </a
                                    >, you will need to create a free account if
                                    you don't already have one.
                                </p>
                                <InputError
                                    :message="form.errors.elevenlabs_api_key"
                                />
                            </div>

                            <!-- OpenAI API Key -->
                            <div class="space-y-2">
                                <Label for="openai_api_key"
                                    >OpenAI API Key</Label
                                >
                                <Input
                                    id="openai_api_key"
                                    v-model="form.openai_api_key"
                                    type="password"
                                    autocomplete="off"
                                    placeholder="OpenAI API Key"
                                />
                                <p class="text-xs text-muted-foreground">
                                    Required for OpenAI TTS voices.
                                </p>
                                <InputError
                                    :message="form.errors.openai_api_key"
                                />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <Button :disabled="form.processing"
                                >Save Settings</Button
                            >

                            <Transition
                                enter-from-class="opacity-0"
                                leave-to-class="opacity-0"
                                class="transition ease-in-out"
                            >
                                <p
                                    v-if="form.recentlySuccessful"
                                    class="text-sm text-muted-foreground"
                                >
                                    Saved.
                                </p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
