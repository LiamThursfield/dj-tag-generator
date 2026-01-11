<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string;
    availableServices?: string[];
}>();

const emit = defineEmits(['update:modelValue']);

const services = [
    { id: 'elevenlabs', name: 'ElevenLabs', description: 'Premium, ultra-realistic voices.', disabled: false },
    { id: 'openai', name: 'OpenAI (Coming Soon)', description: 'Fast, cost-effective, great quality.', disabled: true },
];

const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="service in services" :key="service.id">
            <input
                :id="service.id"
                class="sr-only peer"
                :disabled="service.disabled"
                type="radio"
                :value="service.id"
                v-model="value"
            />
            <label
                :for="service.id"
                class="flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-checked:border-primary transition-all"
                :class="{
                    'border-primary bg-accent/10': value === service.id,
                    'opacity-50 cursor-not-allowed': service.disabled,
                    'cursor-pointer': !service.disabled
                }"
            >
                <span class="text-lg font-semibold mb-1">{{ service.name }}</span>
                <span class="text-sm text-muted-foreground text-center">{{ service.description }}</span>
            </label>
        </div>
    </div>
</template>
