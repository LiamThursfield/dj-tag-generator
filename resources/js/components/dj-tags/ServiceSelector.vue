<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string;
    availableServices?: string[];
}>();

const emit = defineEmits(['update:modelValue']);

const services = [
    {
        id: 'elevenlabs',
        name: 'ElevenLabs',
        description: 'Premium, ultra-realistic voices.',
        disabled: false,
    },
    {
        id: 'openai',
        name: 'OpenAI (Coming Soon)',
        description: 'Fast, cost-effective, great quality.',
        disabled: true,
    },
];

const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div v-for="service in services" :key="service.id">
            <input
                :id="service.id"
                class="peer sr-only"
                :disabled="service.disabled"
                type="radio"
                :value="service.id"
                v-model="value"
            />
            <label
                :for="service.id"
                class="flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 transition-all peer-checked:border-primary hover:bg-accent hover:text-accent-foreground"
                :class="{
                    'border-primary bg-accent/10': value === service.id,
                    'cursor-not-allowed opacity-50': service.disabled,
                    'cursor-pointer': !service.disabled,
                }"
            >
                <span class="mb-1 font-semibold">{{
                    service.name
                }}</span>
                <span class="text-center text-sm text-muted-foreground">{{
                    service.description
                }}</span>
            </label>
        </div>
    </div>
</template>
