<script setup lang="ts">
import { index } from '@/routes/voices';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

const props = defineProps<{
    modelValue: string;
    service: string;
    initialVoices?: any[]; // Allow passing voices directly if available
}>();

const emit = defineEmits(['update:modelValue']);

interface Voice {
    id: string;
    name: string;
    description?: string;
    gender?: string;
    preview_url?: string;
}

const voices = ref<Voice[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

const fetchVoices = async () => {
    if (!props.service) return;

    loading.value = true;
    error.value = null;
    voices.value = [];

    try {
        const response = await axios.get(
            index.url({ query: { service: props.service } }),
        );

        // Handle array response or object response
        const data = response.data.voices;
        if (Array.isArray(data)) {
            voices.value = data;
        } else {
            // If it's an object (key-value), convert to array
            voices.value = Object.values(data);
        }

        // Select first voice if nothing selected
        if (!props.modelValue && voices.value.length > 0) {
            emit('update:modelValue', voices.value[0].id);
        }
    } catch (err: any) {
        console.error('Failed to fetch voices:', err);
        error.value = err.response?.data?.error || 'Failed to load voices';
    } finally {
        loading.value = false;
    }
};

// Fetch when service changes
watch(
    () => props.service,
    () => {
        emit('update:modelValue', ''); // Reset selection
        fetchVoices();
    },
);

onMounted(() => {
    fetchVoices();
});
</script>

<template>
    <div class="space-y-2">
        <label class="block text-sm font-medium text-muted-foreground">
            Voice
        </label>

        <div
            v-if="loading"
            class="flex items-center space-x-2 text-sm text-muted-foreground"
        >
            <svg
                class="mr-3 -ml-1 h-5 w-5 animate-spin text-primary"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>
            Loading voices...
        </div>

        <div v-else-if="error" class="text-sm text-destructive">
            {{ error }}
        </div>

        <div v-else class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            <button
                v-for="voice in voices"
                :key="voice.id"
                type="button"
                @click="emit('update:modelValue', voice.id)"
                class="relative flex flex-col items-start rounded-lg border p-3 text-left transition-all hover:bg-accent"
                :class="
                    modelValue === voice.id
                        ? 'border-primary bg-primary/5 ring-1 ring-primary'
                        : 'border-input bg-card'
                "
            >
                <span class="text-sm font-medium">{{ voice.name }}</span>
                <span
                    v-if="voice.description"
                    class="line-clamp-1 text-xs text-muted-foreground"
                >
                    {{ voice.description }}
                </span>

                <!-- Helper badge for gender if available -->
                <span
                    v-if="voice.gender"
                    class="absolute top-2 right-2 flex h-2 w-2"
                >
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                        :class="modelValue === voice.id ? 'bg-primary' : ''"
                    ></span>
                    <!-- Just simple dot is fine -->
                </span>
            </button>
        </div>

        <p
            v-if="!loading && !error && voices.length === 0"
            class="text-sm text-muted-foreground"
        >
            No voices found for this service.
        </p>
    </div>
</template>
