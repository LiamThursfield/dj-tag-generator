<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { computed } from 'vue';
import { dashboard } from '@/routes';
import { index, create, show } from '@/routes/dj-tags';

const props = defineProps<{
    tag: {
        id: number;
        text: string;
        service: string;
        voice_id: string;
        status: 'pending' | 'processing' | 'completed' | 'failed';
        audio_path: string | null;
        duration: number | null;
        error_message: string | null;
        created_at: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard.url() },
    { title: 'DJ Tags', href: index.url() },
    { title: `Tag #${props.tag.id}`, href: show.url(props.tag.id) },
];

const statusColor = computed(() => {
    switch (props.tag.status) {
        case 'completed': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'failed': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
});

const audioUrl = computed(() => {
    if (!props.tag.audio_path) return null;
    // Assuming audio_path is relative to storage root and we have a storage link or route
    // In a real app, use a proper URL generator helper or accessor on the model
    return `/storage/${props.tag.audio_path}`;
});
</script>

<template>
    <Head :title="`Tag #${tag.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
            <div class="bg-white dark:bg-[#18181b] shadow overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-800">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            DJ Tag Details
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Generated via {{ tag.service }}
                        </p>
                    </div>
                    <span 
                        class="px-2.5 py-0.5 rounded-full text-xs font-medium uppercase tracking-wide"
                        :class="statusColor"
                    >
                        {{ tag.status }}
                    </span>
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
                        
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Voice ID
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ tag.voice_id }}
                            </dd>
                        </div>

                        <div v-if="tag.status === 'completed' && audioUrl" class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Audio
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                <audio controls class="w-full">
                                    <source :src="audioUrl" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                                <div class="mt-2 text-right">
                                    <a 
                                        :href="audioUrl" 
                                        download
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium"
                                    >
                                        Download Audio
                                    </a>
                                </div>
                            </dd>
                        </div>

                         <div v-if="tag.status === 'failed'" class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-red-50 dark:bg-red-900/10">
                            <dt class="text-sm font-medium text-red-500 dark:text-red-400">
                                Error Message
                            </dt>
                            <dd class="mt-1 text-sm text-red-700 dark:text-red-300 sm:mt-0 sm:col-span-2">
                                {{ tag.error_message }}
                            </dd>
                        </div>
                    </dl>
                </div>
                
                <div class="bg-gray-50 dark:bg-[#1f1f22] px-4 py-4 sm:px-6 flex justify-between">
                     <Link 
                        :href="index.url()" 
                        class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-sm font-medium flex items-center"
                    >
                        &larr; Back to List
                    </Link>

                    <Link 
                        :href="create.url()" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md text-sm shadow transition-colors"
                    >
                        Create Another
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
