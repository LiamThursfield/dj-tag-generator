<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, create, show } from '@/routes/dj-tags';
import { Button } from '@/components/ui/button';

defineProps<{
    tags: {
        data: any[];
        links: any[];
        meta?: any;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard.url() },
    { title: 'DJ Tags', href: index.url() },
];

const statusClass = (status: string) => {
     switch (status) {
        case 'completed': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'failed': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
};
</script>

<template>
    <Head title="My DJ Tags" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-4xl mx-auto p-4 w-full sm:p-6 lg:p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">My DJ Tags</h1>

                <Button as-child>
                    <Link
                        :href="create.url()"
                    >
                        + New Tag
                    </Link>
                </Button>
            </div>

            <div class="bg-white dark:bg-[#18181b] shadow overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-800">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="tag in tags.data" :key="tag.id">
                        <Link
                            :href="show.url(tag.id)"
                            class="block hover:bg-gray-50 dark:hover:bg-zinc-900 transition duration-150 ease-in-out"
                        >
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-primary truncate w-2/3">
                                        {{ tag.text }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full uppercase"
                                            :class="statusClass(tag.status)"
                                        >
                                            {{ tag.status }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            {{ tag.service }} / {{ tag.voice_id }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                                        <p>
                                            Created {{ new Date(tag.created_at).toLocaleDateString() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </li>
                    <li v-if="tags.data.length === 0" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                        No tags generated yet. Click "New Tag" to get started!
                    </li>
                </ul>

                <!-- Simple Pagination -->
                <div v-if="tags.links && tags.links.length > 3" class="bg-white dark:bg-[#18181b] px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <!-- Mobile Pagination -->
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <!-- Use inertia Links for pagination -->
                                     <component
                                        :is="link.url ? Link : 'span'"
                                        v-for="link in tags.links"
                                        :key="link.label"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-700"
                                        :class="{ 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': link.active, 'text-gray-500 cursor-default': !link.url }"
                                    />
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
