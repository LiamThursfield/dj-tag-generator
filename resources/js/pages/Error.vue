<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    status: number;
}

const props = defineProps<Props>();

const title = computed(() => {
    return (
        {
            503: 'Service Unavailable',
            500: 'Server Error',
            404: 'Page Not Found',
            403: 'Forbidden',
        }[props.status] || 'Error'
    );
});

const description = computed(() => {
    return (
        {
            503: 'Sorry, we are doing some maintenance. Please check back soon.',
            500: 'Whoops, something went wrong on our servers.',
            404: 'Sorry, the page you are looking for could not be found.',
            403: 'Sorry, you are forbidden from accessing this page.',
        }[props.status] || 'An unexpected error occurred.'
    );
});
</script>

<template>
    <Head :title="title" />

    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <div class="w-full max-w-md">
            <div class="flex flex-col gap-6 text-center">
                <div class="flex flex-col items-center gap-2">
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-2xl bg-primary/10 p-4 text-primary"
                    >
                        <AppLogoIcon class="size-10" />
                    </div>
                </div>

                <div class="space-y-2">
                    <h1 class="text-3xl font-bold tracking-tight">
                        {{ title }}
                    </h1>
                    <p class="text-muted-foreground">{{ description }}</p>
                </div>

                <div class="flex justify-center">
                    <Button as-child size="lg">
                        <Link href="/"> Go Back Home </Link>
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
