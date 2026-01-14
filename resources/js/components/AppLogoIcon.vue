<script setup lang="ts">
import { AudioLines } from 'lucide-vue-next';
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <AudioLines />
</template>
