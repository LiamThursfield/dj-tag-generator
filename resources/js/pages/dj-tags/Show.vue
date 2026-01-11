<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';
import { dashboard } from '@/routes';
import { index, create, show, reprocess } from '@/routes/dj-tags';

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
        case 'completed': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'failed': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
};

const getAudioUrl = (path: string | null) => {
    if (!path) return null;
    return `/storage/${path}`;
};

const latestVersion = computed(() => props.tag.versions[0] || null);

const form = useForm({
    audio_effects: latestVersion.value?.audio_effects || {
        reverb: null,
        pitch: 0,
        speed: 1.0,
        bass_boost: false,
        normalize: true,
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
const compareVersion = computed(() => 
    props.tag.versions.find(v => v.id === compareVersionId.value) || null
);
</script>

<template>
    <Head :title="`Tag #${tag.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
            <!-- Master Info -->
            <div class="bg-white dark:bg-[#18181b] shadow overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-800">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            DJ Tag Details
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Generated via {{ tag.service }} (Voice: {{ tag.voice_id }})
                        </p>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200 dark:sm:divide-gray-700">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Script Text
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 italic">
                                "{{ tag.text }}"
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Versions List -->
                <div class="lg:col-span-2 space-y-4">
                    <div v-for="version in tag.versions" :key="version.id" 
                        class="bg-white dark:bg-[#18181b] shadow overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-800"
                        :class="{'border-indigo-500 ring-1 ring-indigo-500': version.id === latestVersion?.id}"
                    >
                        <div class="px-4 py-4 sm:px-6 flex justify-between items-center bg-gray-50/50 dark:bg-white/5">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100 text uppercase tracking-wider">
                                    Version {{ version.version_number }}
                                </span>
                                <span v-if="version.id === latestVersion?.id" class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300 uppercase">
                                    Current
                                </span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide" :class="getStatusColor(version.status)">
                                    {{ version.status }}
                                </span>
                                <button v-if="version.status === 'completed' && version.id !== latestVersion?.id" 
                                    @click="compareVersionId = compareVersionId === version.id ? null : version.id"
                                    class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                >
                                    {{ compareVersionId === version.id ? 'Hide Comparison' : 'Compare' }}
                                </button>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6 space-y-4">
                            <!-- Comparison Player if active -->
                            <div v-if="compareVersionId === version.id" class="p-4 bg-indigo-50 dark:bg-indigo-900/10 rounded-lg space-y-3 border border-indigo-100 dark:border-indigo-900/30">
                                <div class="flex justify-between items-center text-xs font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-widest">
                                    <span>Comparison Comparison</span>
                                    <span>Version {{ latestVersion?.version_number }} (Current)</span>
                                </div>
                                <audio controls class="w-full h-8">
                                    <source :src="getAudioUrl(latestVersion?.audio_path)" type="audio/mpeg">
                                </audio>
                            </div>

                            <div v-if="version.status === 'completed' && version.audio_path" class="space-y-3">
                                <audio controls class="w-full">
                                    <source :src="getAudioUrl(version.audio_path)" type="audio/mpeg">
                                </audio>
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 italic">
                                        Effects: {{ version.audio_effects ? Object.entries(version.audio_effects).filter(([_, v]) => v && v !== 1.0 && v !== 0).map(([k, v]) => `${k}: ${v}`).join(', ') || 'None' : 'None' }}
                                    </div>
                                    <a :href="getAudioUrl(version.audio_path)" download class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-800 dark:hover:text-indigo-300 uppercase tracking-wider">
                                        Download
                                    </a>
                                </div>
                            </div>

                            <div v-if="version.status === 'failed'" class="p-3 bg-red-50 dark:bg-red-900/10 rounded border border-red-100 dark:border-red-900/30">
                                <p class="text-xs text-red-600 dark:text-red-400 font-medium">Error: {{ version.error_message }}</p>
                            </div>

                            <div v-if="version.status === 'processing' || version.status === 'pending'" class="flex items-center justify-center p-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reprocess Sidebar -->
                <div class="space-y-4">
                    <div class="bg-white dark:bg-[#18181b] shadow sm:rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden sticky top-6">
                        <div class="px-4 py-4 sm:px-6 bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-gray-800">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                                Experiment with Effects
                            </h4>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">
                                Create a new version using the raw master audio. No extra AI credits used.
                            </p>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Reverb</label>
                                    <select v-model="form.audio_effects.reverb" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-[#09090b] text-sm focus:ring-indigo-500 dark:text-gray-300">
                                        <option :value="null">None</option>
                                        <option value="small_room">Small Room</option>
                                        <option value="large_hall">Large Hall</option>
                                        <option value="stadium">Stadium</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Pitch Shift ({{ form.audio_effects.pitch }} semitones)</label>
                                    <input type="range" v-model.number="form.audio_effects.pitch" min="-12" max="12" step="1" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-indigo-600" />
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Speed ({{ form.audio_effects.speed }}x)</label>
                                    <input type="range" v-model.number="form.audio_effects.speed" min="0.5" max="2.0" step="0.1" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-indigo-600" />
                                </div>

                                <div class="flex items-center justify-between">
                                    <label class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Bass Boost</label>
                                    <input type="checkbox" v-model="form.audio_effects.bass_boost" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500 bg-transparent" />
                                </div>

                                <div class="flex items-center justify-between">
                                    <label class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Normalize</label>
                                    <input type="checkbox" v-model="form.audio_effects.normalize" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500 bg-transparent" />
                                </div>
                            </div>

                            <button 
                                @click="submitReprocess"
                                :disabled="form.processing"
                                class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold py-2.5 px-4 rounded shadow transition-all text-xs uppercase tracking-widest"
                            >
                                {{ form.processing ? 'Generating...' : 'Create New Version' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-start pt-4 border-t border-gray-200 dark:border-gray-800">
                 <Link 
                    :href="index.url()" 
                    class="text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white uppercase tracking-widest flex items-center"
                >
                    &larr; Back to Library
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

