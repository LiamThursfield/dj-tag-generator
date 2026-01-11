<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const model = defineModel<{
    pitch: number;
    speed: number;
    reverb: string | null;
    bass_boost: boolean;
    tremolo: boolean;
    echo: boolean;
    chorus: boolean;
    lofi_telephone: boolean;
    bitcrush: boolean;
    normalize: boolean;
}>({ required: true, local: true });

defineProps<{
    compact?: boolean;
}>();
</script>

<template>
    <div class="space-y-6">
        <div
            :class="[
                compact ? 'space-y-4' : 'grid grid-cols-1 gap-6 md:grid-cols-2',
            ]"
        >
            <!-- Basic Controls -->
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label
                        :class="[
                            compact
                                ? 'text-xs font-bold tracking-wider text-muted-foreground uppercase'
                                : 'text-sm font-medium',
                        ]"
                    >
                        Pitch Shift ({{ model.pitch > 0 ? '+' : ''
                        }}{{ model.pitch }} semitones)
                    </Label>
                    <input
                        type="range"
                        v-model.number="model.pitch"
                        min="-12"
                        max="12"
                        step="1"
                        class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-muted accent-primary"
                    />
                </div>

                <div class="grid gap-2">
                    <Label
                        :class="[
                            compact
                                ? 'text-xs font-bold tracking-wider text-muted-foreground uppercase'
                                : 'text-sm font-medium',
                        ]"
                    >
                        Playback Speed ({{ model.speed }}x)
                    </Label>
                    <input
                        type="range"
                        v-model.number="model.speed"
                        min="0.5"
                        max="2.0"
                        step="0.1"
                        class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-muted accent-primary"
                    />
                </div>

                <div class="grid gap-2">
                    <Label
                        :class="[
                            compact
                                ? 'text-xs font-bold tracking-wider text-muted-foreground uppercase'
                                : 'text-sm font-medium',
                        ]"
                        >Reverb</Label
                    >
                    <Select v-model="model.reverb">
                        <SelectTrigger :class="[compact ? 'h-8 text-xs' : '']">
                            <SelectValue placeholder="Select Reverb" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="none">None</SelectItem>
                                <SelectItem value="small_room"
                                    >Small Room</SelectItem
                                >
                                <SelectItem value="large_hall"
                                    >Large Hall</SelectItem
                                >
                                <SelectItem value="stadium">Stadium</SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <!-- Toggleable Effects -->
            <div class="space-y-3">
                <div
                    v-for="(label, key) in {
                        bass_boost: 'Bass Boost',
                        tremolo: 'Tremolo',
                        echo: 'Echo',
                        chorus: 'Chorus',
                        lofi_telephone: 'Lo-Fi / Telephone',
                        bitcrush: 'Bitcrush / Distortion',
                        normalize: 'Normalize',
                    } as const"
                    :key="key"
                    class="flex items-center justify-between"
                >
                    <Label
                        :for="key"
                        :class="[
                            'flex-1 cursor-pointer select-none',
                            compact
                                ? 'text-xs font-bold tracking-wider text-muted-foreground uppercase'
                                : 'text-sm font-medium',
                        ]"
                    >
                        {{ label }}
                    </Label>
                    <Checkbox
                        :id="key"
                        v-model="model[key]"
                        class="cursor-pointer"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
