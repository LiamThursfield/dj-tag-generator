<script setup lang="ts">
import { create } from '@/actions/App/Http/Controllers/DjTagController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { edit } from '@/routes/settings/api-services';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { AlertCircle, Mic, Music } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    tagsCount: number;
    tagLimit: number;
    elevenLabsStatus: {
        configured: boolean;
        remainingCredits: number | null;
        error: string | null;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const formatNumber = (num: number) => {
    return new Intl.NumberFormat().format(num);
};

const hasReachedTagLimit = computed(
    () => props.tagsCount >= props.tagLimit,
)
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="grid auto-rows-min gap-4 lg:grid-cols-3">
                <!-- ElevenLabs Status Card -->
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0"
                    >
                        <CardTitle class="text-sm font-medium">
                            ElevenLabs Status
                        </CardTitle>
                        <Mic class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="flex-1">
                        <div v-if="props.elevenLabsStatus.configured">
                            <div
                                v-if="props.elevenLabsStatus.error"
                                class="flex items-center gap-2 text-destructive"
                            >
                                <AlertCircle class="h-4 w-4" />
                                <span class="text-sm font-medium">{{
                                        props.elevenLabsStatus.error
                                    }}</span>
                            </div>
                            <div v-else>
                                <div class="text-2xl font-bold">
                                    {{
                                        formatNumber(
                                            props.elevenLabsStatus
                                                .remainingCredits ?? 0,
                                        )
                                    }}
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    Characters remaining
                                </p>
                            </div>
                        </div>
                        <div v-else class="flex flex-col gap-2">
                            <div
                                class="flex items-center gap-2 text-muted-foreground"
                            >
                                <AlertCircle class="h-4 w-4" />
                                <span class="text-sm">Not Configured</span>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter v-if="!props.elevenLabsStatus.configured">
                        <Button
                            as-child
                            variant="secondary"
                            class="cursor-pointer mt-2 w-full"
                        >
                            <Link :href="edit()">
                                Setup API Key
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>

                <!-- Create New Tag Card -->
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0"
                    >
                        <CardTitle class="text-sm font-medium">
                            Create New Tag
                        </CardTitle>
                        <div class="h-4 w-4 text-muted-foreground">+</div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">New Tag</div>
                        <p class="text-xs text-muted-foreground">
                            Generate a new DJ drop
                        </p>
                    </CardContent>
                    <CardFooter>
                        <Button
                            v-if="!hasReachedTagLimit"
                            as-child
                            class="w-full"
                        >
                            <Link :href="create()">Create Now</Link>
                        </Button>
                        <Button
                            v-else
                            class="w-full"
                            variant="outline"
                            disabled
                        >
                            Tag Limit Reached
                        </Button>
                    </CardFooter>
                </Card>
                <!-- Tag Usage Card -->
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0"
                    >
                        <CardTitle class="text-sm font-medium">
                            Tag Usage
                        </CardTitle>
                        <Music class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ props.tagsCount }} / {{ props.tagLimit }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            DJ Tags created
                        </p>
                        <div class="mt-4 h-2 w-full rounded-full bg-secondary">
                            <div
                                class="h-2 rounded-full transition-all"
                                :class="{
                                    'bg-destructive': hasReachedTagLimit,
                                    'bg-primary': !hasReachedTagLimit,
                                }"
                                :style="{
                                    width: `${Math.min((props.tagsCount / props.tagLimit) * 100, 100)}%`,
                                }"
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
