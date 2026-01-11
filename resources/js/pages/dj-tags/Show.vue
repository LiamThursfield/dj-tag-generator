<script setup lang="ts">
import AudioEffectsSelector from '@/components/dj-tags/AudioEffectsSelector.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index, reprocess, show } from '@/routes/dj-tags';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface TagVersion {
    id: number;
    version_number: number;
    audio_effects: Record<string, any> | null;
    audio_path: string | null;
    duration: number | null;
    status: 'pending' | 'processing' | 'completed' | 'failed';
    error_message: string | null;
    created_at: string;
}

const props = defineProps<{
    tag: {
        id: number;
        text: string;
        service: string;
        voice_id: string;
        format: string;
        raw_audio_path: string | null;
        raw_audio_duration: number | null;
        created_at: string;
        versions: TagVersion[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard.url() },
    { title: 'DJ Tags', href: index.url() },
    { title: `Tag #${props.tag.id}`, href: show.url(props.tag.id) },
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'completed':
            return 'bg-emerald-500/10 text-emerald-500';
        case 'failed':
            return 'bg-destructive/10 text-destructive';
        case 'processing':
            return 'bg-primary/10 text-primary';
        default:
            return 'bg-muted text-muted-foreground';
    }
};

const getAudioUrl = (path: string | null) => {
    if (!path) return null;
    return `/storage/${path}`;
};

const latestVersion = computed(() => props.tag.versions[0] || null);

const defaultAudioEffects = {
    pitch: 0,
    speed: 1.0,
    reverb: 'none',
    bass_boost: false,
    flanger: false,
    tremolo: false,
    echo: false,
    chorus: false,
    lofi_telephone: false,
    bitcrush: false,
    normalize: true,
};

const form = useForm({
    audio_effects: {
        ...defaultAudioEffects,
        ...(latestVersion.value?.audio_effects || {}),
    },
});

const submitReprocess = () => {
    form.post(reprocess.url(props.tag.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Success notification or reset if needed
        },
    });
};

const compareVersionId = ref<number | null>(null);
const compareVersion = computed(
    () =>
        props.tag.versions.find((v) => v.id === compareVersionId.value) || null,
);
</script>

<template>
    <Head :title="`Tag #${tag.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-5xl space-y-6 p-4 sm:p-6 lg:p-8">
            <!-- Master Info -->
            <div
                class="overflow-hidden border border-border bg-card shadow sm:rounded-lg"
            >
                <div
                    class="flex items-center justify-between px-4 py-5 sm:px-6"
                >
                    <div>
                        <h3
                            class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                        >
                            DJ Tag Details
                        </h3>
                        <p
                            class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400"
                        >
                            Generated via {{ tag.service }} (Voice:
                            {{ tag.voice_id }})
                        </p>
                    </div>
                </div>

                <div
                    class="border-t border-gray-200 px-4 py-5 sm:p-0 dark:border-gray-700"
                >
                    <dl
                        class="sm:divide-y sm:divide-gray-200 dark:sm:divide-gray-700"
                    >
                        <div
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <dt
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Script Text
                            </dt>
                            <dd
                                class="mt-1 text-sm text-gray-900 italic sm:col-span-2 sm:mt-0 dark:text-gray-100"
                            >
                                "{{ tag.text }}"
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Versions List -->
                <div class="space-y-4 lg:col-span-2">
                    <div
                        v-for="version in tag.versions"
                        :key="version.id"
                        class="overflow-hidden border border-border bg-card shadow sm:rounded-lg"
                        :class="{
                            'border-primary ring-1 ring-primary':
                                version.id === latestVersion?.id,
                        }"
                    >
                        <div
                            class="flex items-center justify-between bg-muted/30 px-4 py-4 sm:px-6"
                        >
                            <div class="flex items-center space-x-3">
                                <span
                                    class="text-sm font-bold tracking-wider text-foreground uppercase"
                                >
                                    Version {{ version.version_number }}
                                </span>
                                <Badge
                                    v-if="version.id === latestVersion?.id"
                                    variant="secondary"
                                    class="text-xs uppercase"
                                >
                                    Current
                                </Badge>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span
                                    class="rounded-full px-2.5 py-0.5 text-xs font-bold tracking-wide uppercase"
                                    :class="getStatusColor(version.status)"
                                >
                                    {{ version.status }}
                                </span>
                                <button
                                    v-if="
                                        version.status === 'completed' &&
                                        version.id !== latestVersion?.id
                                    "
                                    @click="
                                        compareVersionId =
                                            compareVersionId === version.id
                                                ? null
                                                : version.id
                                    "
                                    class="text-xs font-semibold text-primary hover:text-primary/80"
                                >
                                    {{
                                        compareVersionId === version.id
                                            ? 'Hide Comparison'
                                            : 'Compare'
                                    }}
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4 p-4 sm:p-6">
                            <!-- Comparison Player if active -->
                            <div
                                v-if="compareVersionId === version.id"
                                class="space-y-3 rounded-lg border border-primary/20 bg-primary/5 p-4"
                            >
                                <div
                                    class="flex items-center justify-between text-xs font-bold tracking-widest text-primary uppercase"
                                >
                                    <span>Comparison</span>
                                    <span
                                        >Version
                                        {{ latestVersion?.version_number }}
                                        (Current)</span
                                    >
                                </div>
                                <audio controls class="h-8 w-full">
                                    <source
                                        :src="
                                            getAudioUrl(
                                                latestVersion?.audio_path,
                                            )
                                        "
                                        type="audio/mpeg"
                                    />
                                </audio>
                            </div>

                            <div
                                v-if="
                                    version.status === 'completed' &&
                                    version.audio_path
                                "
                                class="space-y-3"
                            >
                                <audio controls class="w-full">
                                    <source
                                        :src="getAudioUrl(version.audio_path)"
                                        type="audio/mpeg"
                                    />
                                </audio>
                                <div class="flex items-center justify-between">
                                    <div
                                        class="text-xs text-gray-500 italic dark:text-gray-400"
                                    >
                                        Effects:
                                        {{
                                            version.audio_effects
                                                ? Object.entries(
                                                      version.audio_effects,
                                                  )
                                                      .filter(
                                                          ([_, v]) =>
                                                              v &&
                                                              v !== 1.0 &&
                                                              v !== 0,
                                                      )
                                                      .map(
                                                          ([k, v]) =>
                                                              `${k}: ${v}`,
                                                      )
                                                      .join(', ') || 'None'
                                                : 'None'
                                        }}
                                    </div>
                                    <a
                                        :href="getAudioUrl(version.audio_path)"
                                        download
                                        class="text-xs font-bold tracking-wider text-indigo-600 uppercase hover:text-indigo-800 hover:underline dark:text-indigo-400 dark:hover:text-indigo-300"
                                    >
                                        Download
                                    </a>
                                </div>
                            </div>

                            <div
                                v-if="version.status === 'failed'"
                                class="rounded border border-red-100 bg-red-50 p-3 dark:border-red-900/30 dark:bg-red-900/10"
                            >
                                <p
                                    class="text-xs font-medium text-red-600 dark:text-red-400"
                                >
                                    Error: {{ version.error_message }}
                                </p>
                            </div>

                            <div
                                v-if="
                                    version.status === 'processing' ||
                                    version.status === 'pending'
                                "
                                class="flex items-center justify-center p-8"
                            >
                                <div
                                    class="h-8 w-8 animate-spin rounded-full border-b-2 border-primary"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reprocess Sidebar -->
                <div class="space-y-4">
                    <div
                        class="sticky top-6 overflow-hidden border border-border bg-card shadow sm:rounded-lg"
                    >
                        <div
                            class="border-b border-border bg-muted/30 px-4 py-4 sm:px-6"
                        >
                            <h4
                                class="text-sm font-bold tracking-wider text-foreground uppercase"
                            >
                                Experiment with Effects
                            </h4>
                            <p class="mt-1 text-xs text-muted-foreground">
                                Create a new version using the raw master audio.
                                No extra AI credits used.
                            </p>
                        </div>
                        <div class="space-y-4 p-4">
                            <!-- Audio Effects Selector -->
                            <AudioEffectsSelector
                                v-model="form.audio_effects"
                                compact
                            />

                            <Button
                                @click="submitReprocess"
                                :disabled="form.processing"
                                class="mt-4 h-10 w-full text-xs tracking-widest uppercase"
                            >
                                {{
                                    form.processing
                                        ? 'Generating...'
                                        : 'Create New Version'
                                }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-start border-t border-border pt-4">
                <Link
                    :href="index.url()"
                    class="flex items-center text-xs font-bold tracking-widest text-muted-foreground uppercase hover:text-foreground"
                >
                    &larr; Back to DJ Tags
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
