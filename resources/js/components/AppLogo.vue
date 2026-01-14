<script setup lang="ts">
import { AudioLines } from 'lucide-vue-next';
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-primary text-primary-foreground"
    >
        <AudioLines class="size-5 fill-current" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >DJ Tag Generator</span
        >
    </div>
</template>
