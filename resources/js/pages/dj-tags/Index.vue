<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { create, index, show } from '@/routes/dj-tags';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

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
</script>

<template>
    <Head title="My DJ Tags" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-4xl p-4 sm:p-6 lg:p-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-foreground">My DJ Tags</h1>

                <Button as-child>
                    <Link :href="create.url()"> + New Tag </Link>
                </Button>
            </div>

            <div
                class="overflow-hidden border border-border bg-card shadow sm:rounded-lg"
            >
                <ul role="list" class="divide-y divide-border">
                    <li v-for="tag in tags.data" :key="tag.id">
                        <Link
                            :href="show.url(tag.id)"
                            class="block transition duration-150 ease-in-out hover:bg-muted/50"
                        >
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p
                                        class="w-2/3 truncate text-sm font-medium text-primary"
                                    >
                                        {{ tag.text }}
                                    </p>
                                    <div class="ml-2 flex flex-shrink-0">
                                        <p
                                            class="inline-flex rounded-full px-2 text-xs leading-5 font-bold tracking-widest uppercase"
                                            :class="
                                                statusClass(
                                                    tag.latest_version
                                                        ?.status || 'pending',
                                                )
                                            "
                                        >
                                            {{
                                                tag.latest_version?.status ||
                                                'pending'
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p
                                            class="flex items-center text-sm text-muted-foreground"
                                        >
                                            {{ tag.service }} /
                                            {{ tag.voice_id }}
                                        </p>
                                    </div>
                                    <div
                                        class="mt-2 flex items-center text-sm text-muted-foreground sm:mt-0"
                                    >
                                        <p>
                                            Created
                                            {{
                                                new Date(
                                                    tag.created_at,
                                                ).toLocaleDateString()
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </li>
                    <li
                        v-if="tags.data.length === 0"
                        class="px-4 py-12 text-center text-muted-foreground"
                    >
                        No tags generated yet. Click "New Tag" to get started!
                    </li>
                </ul>

                <!-- Simple Pagination -->
                <div
                    v-if="tags.links && tags.links.length > 3"
                    class="border-t border-border bg-card px-4 py-3 sm:px-6"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <!-- Mobile Pagination -->
                        </div>
                        <div
                            class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center"
                        >
                            <div>
                                <nav
                                    class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm"
                                    aria-label="Pagination"
                                >
                                    <!-- Use inertia Links for pagination -->

                                    <component
                                        :is="link.url ? Link : 'span'"
                                        v-for="link in tags.links"
                                        :key="link.label"
                                        :href="link.url"
                                        class="relative inline-flex items-center border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
                                        :class="{
                                            'z-10 border-primary bg-primary/10 text-primary':
                                                link.active,
                                            'cursor-default text-muted-foreground':
                                                !link.url,
                                        }"
                                    >
                                        <span v-html="link.label"></span>
                                    </component>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
