<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    AudioLines,
    Music,
    Radio,
    Sparkles,
    Zap,
} from 'lucide-vue-next';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    plans?: Array<{
        id: number;
        name: string;
        description: string;
        limits: any;
        price_monthly: number;
        price_yearly: number;
    }>;
}>();

const year = new Date().getFullYear();

const futurePlans = [
    {
        name: 'Professional',
        description: 'For power users who need more volume.',
        limits: {
            dj_tag_limit: 'More',
            dj_tag_version_limit: 'More',
        },
        price_monthly: 'TBD',
        price_yearly: 'TBD',
    },
    {
        name: 'Unlimited',
        description: 'For users who need unlimited access.',
        limits: {
            dj_tag_limit: 'Unlimited',
            dj_tag_version_limit: 'Unlimited',
        },
        price_monthly: 'TBD',
        price_yearly: 'TBD',
    },
];
</script>

<template>
    <Head title="DJ Tag Generator" />

    <!-- Dark Mode Wrapper with Noise Texture Overlay -->
    <div
        class="min-h-screen overflow-x-hidden bg-[#0a0a0a] font-sans text-white antialiased selection:bg-orange-500/30 selection:text-orange-200"
    >
        <!-- Navigation - Floating Glass Pill -->
        <header class="fixed top-6 z-50 w-full px-4">
            <div
                class="mx-auto max-w-5xl rounded-md border border-white/10 bg-black/50 px-6 py-3 backdrop-blur-md transition-all duration-300 hover:border-white/20 hover:bg-black/60"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div
                            class="mr-2 flex h-8 w-8 items-center justify-center rounded-md bg-gradient-to-br from-orange-500 to-orange-700 shadow-[0_0_15px_rgba(249,115,22,0.5)]"
                        >
                            <AudioLines
                                class="h-5 w-5 fill-primary-foreground text-primary-foreground"
                            />
                        </div>
                        <span
                            class="text-lg font-bold tracking-tight text-foreground"
                        >
                            DJ Tag Generator
                        </span>
                    </div>

                    <nav v-if="canLogin" class="flex gap-4">
                        <template v-if="$page.props.auth.user">
                            <Link :href="dashboard()">
                                <Button variant="ghost">Dashboard</Button>
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="login()">
                                <Button
                                    variant="ghost"
                                    class="hidden sm:inline-flex"
                                >
                                    Log in
                                </Button>
                            </Link>
                            <Link v-if="canRegister" :href="register()">
                                <Button
                                    class="bg-primary font-medium text-primary-foreground hover:bg-primary/90"
                                >
                                    Get Started
                                </Button>
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <main class="relative pt-32 pb-20">
            <!-- Hero Section -->
            <div class="relative px-6 lg:px-8">
                <!-- Glowing Background Blob -->
                <div
                    class="absolute top-0 left-1/2 -z-10 h-[500px] w-[500px] -translate-x-1/2 rounded-full bg-orange-600/20 blur-[120px]"
                />

                <div class="mx-auto max-w-3xl text-center">
                    <div
                        class="mb-6 inline-flex items-center rounded-md border border-orange-500/30 bg-orange-500/10 px-3 py-1 text-sm text-orange-300"
                    >
                        <Sparkles class="mr-2 h-3.5 w-3.5" />
                        <span>AI-Powered Audio Generation</span>
                    </div>
                    <h1
                        class="bg-gradient-to-b from-white to-white/60 bg-clip-text text-5xl font-bold tracking-tighter text-transparent sm:text-7xl"
                    >
                        Professional DJ drops <br />
                        in
                        <span
                            class="bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent"
                            >seconds</span
                        >.
                    </h1>
                    <p
                        class="mt-8 text-lg leading-relaxed text-balance text-white/60"
                    >
                        Generate high-quality, custom DJ tags and drops using
                        advanced AI. <br class="hidden sm:block" />Elevate your
                        mixes with professional voiceovers instantly.
                    </p>
                    <div
                        class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row"
                    >
                        <Link v-if="canRegister" :href="register()">
                            <Button
                                class="h-12 bg-primary px-8 text-base font-semibold text-white shadow-[0_0_20px_rgba(234,88,12,0.4)] transition-all hover:scale-105 hover:bg-orange-500 active:scale-95"
                            >
                                Start Creating Free
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </Button>
                        </Link>
                        <Link :href="login()">
                            <Button
                                variant="ghost"
                                class="h-12 px-8 text-white/60 hover:bg-white/5 hover:text-white"
                            >
                                Existing User? Log in
                            </Button>
                        </Link>
                    </div>
                </div>

                <!-- Hero Image with Tilt & Glow -->
                <div class="mt-20 flex justify-center">
                    <div
                        class="group relative max-w-6xl rounded-xl bg-black/40 p-2 shadow-2xl ring-1 shadow-orange-900/20 ring-white/10 backdrop-blur-xl transition-all duration-700 hover:rotate-0 sm:rotate-x-12 sm:hover:scale-[1.02]"
                    >
                        <div
                            class="absolute -inset-1 rounded-xl bg-gradient-to-r from-primary to-pink-500 opacity-20 blur-xl transition-opacity duration-500 group-hover:opacity-40"
                        ></div>
                        <img
                            src="/images/hero-tag-create.png"
                            alt="App screenshot"
                            class="relative z-10 w-full rounded-lg shadow-2xl"
                        />

                        <!-- Floating Glass Card overlay example -->
                        <div
                            class="animate-bounce-slow absolute -right-8 -bottom-8 z-10 hidden lg:block"
                        >
                            <div
                                class="max-w-[200px] rounded border border-white/10 bg-black/60 p-4 shadow-xl backdrop-blur-md"
                            >
                                <div class="mb-2 flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/20 text-primary-foreground"
                                    >
                                        <Music class="h-4 w-4" />
                                    </div>
                                    <div>
                                        <div
                                            class="text-xs font-medium text-primary-foreground"
                                        >
                                            Generation Complete
                                        </div>
                                        <div
                                            class="text-[10px] text-primary-foreground/50"
                                        >
                                            Just now
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="h-2 w-full overflow-hidden rounded-full bg-white/10"
                                >
                                    <div
                                        class="h-full w-full rounded-full bg-primary/80"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Grid Features Section -->
            <div class="mx-auto mt-32 max-w-6xl px-6 lg:px-8">
                <div class="mx-auto mb-16 max-w-2xl text-center">
                    <h2
                        class="text-3xl font-bold tracking-tight text-white sm:text-4xl"
                    >
                        Everything you need
                    </h2>
                    <p class="mt-4 text-white/60">
                        Powerful tools to give your mixes a professional
                        identity.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 gap-6 md:grid-cols-3 md:grid-rows-2"
                >
                    <!-- Large Card -->
                    <div
                        class="group relative col-span-1 overflow-hidden rounded border border-white/10 bg-white/5 p-8 transition-all hover:border-white/20 hover:bg-white/10 md:col-span-2 md:row-span-2"
                    >
                        <div
                            class="absolute top-0 right-0 -m-16 h-64 w-64 rounded-full bg-orange-600/20 blur-[80px]"
                        ></div>
                        <div class="relative z-10">
                            <div
                                class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-500/20 text-orange-400"
                            >
                                <Radio class="h-6 w-6" />
                            </div>
                            <h3 class="mb-2 text-2xl font-bold text-white">
                                Instant Voice Synthesis
                            </h3>
                            <p class="max-w-md text-white/60">
                                Type your script, choose from dozens of
                                professional DJ voices, and generate
                                studio-quality audio in seconds. No recording
                                equipment needed.
                            </p>
                        </div>
                    </div>

                    <!-- Small Card 1 -->
                    <div
                        class="group relative overflow-hidden rounded border border-white/10 bg-white/5 p-8 transition-all hover:border-white/20 hover:bg-white/10"
                    >
                        <h3 class="mb-2 text-lg font-bold text-white">
                            Wait-free Generation
                        </h3>
                        <p class="text-sm text-white/60">
                            Lightning fast processing means no more waiting for
                            expensive voice actors.
                        </p>
                    </div>

                    <!-- Small Card 2 with Gradient -->
                    <div
                        class="group relative overflow-hidden rounded border border-white/10 bg-gradient-to-br from-orange-900/40 to-black p-8 transition-all hover:border-orange-500/30"
                    >
                        <div
                            class="absolute inset-0 bg-orange-500/10 opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>
                        <h3 class="mb-2 text-lg font-bold text-white">
                            Effect Processing
                        </h3>
                        <p class="text-sm text-white/60">
                            Add reverb, delay, and stutter effects
                            automatically.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pricing Section -->
            <div id="pricing" class="mx-auto mt-32 max-w-6xl px-6 lg:px-8">
                <div class="mx-auto mb-16 max-w-2xl text-center">
                    <h2
                        class="text-3xl font-bold tracking-tight text-white sm:text-4xl"
                    >
                        Simple Pricing
                    </h2>
                    <p class="mt-4 text-white/60">
                        Choose the plan that fits your needs. No hidden fees.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Dynamic Plans -->
                    <div
                        v-for="plan in plans"
                        :key="plan.id"
                        class="relative flex flex-col rounded border border-white/10 bg-white/5 p-8 transition-all hover:border-orange-500/30"
                    >
                        <h3 class="mb-4 text-xl font-bold text-white">
                            {{ plan.name }}
                        </h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-white"
                                >£{{ plan.price_monthly }}</span
                            >
                            <span class="ml-2 text-white/40">/ month</span>
                        </div>
                        <p class="mb-6 flex-grow text-white/60">
                            {{ plan.description }}
                        </p>
                        <ul class="mb-8 space-y-3 text-sm text-white/80">
                            <li class="flex items-center">
                                <Zap class="mr-2 h-4 w-4 text-orange-500" />
                                <span
                                    >{{
                                        plan.limits?.dj_tag_limit ?? 5
                                    }}
                                    Tags</span
                                >
                            </li>
                            <li class="flex items-center">
                                <Zap class="mr-2 h-4 w-4 text-orange-500" />
                                <span
                                    >{{
                                        plan.limits?.dj_tag_version_limit ?? 5
                                    }}
                                    Versions per Tag</span
                                >
                            </li>
                        </ul>
                        <Link v-if="canRegister" :href="register()">
                            <Button
                                class="w-full bg-white/10 text-white hover:bg-white/20"
                                >Get Started</Button
                            >
                        </Link>
                    </div>

                    <!-- Professional (Coming Soon) -->
                    <div
                        v-for="plan in futurePlans"
                        :key="plan.name"
                        class="relative flex flex-col rounded border border-white/10 bg-gradient-to-br from-white/5 to-transparent p-8 opacity-75"
                    >
                        <div
                            class="absolute top-4 right-4 rounded-full border border-orange-500/30 bg-orange-500/20 px-3 py-1 text-xs font-medium text-orange-400"
                        >
                            Coming Soon
                        </div>

                        <h3 class="mb-4 text-xl font-bold text-white">
                            {{ plan.name }}
                        </h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-white">
                                {{ plan.price_monthly }}
                            </span>
                            <span class="ml-2 text-white/40">/ month</span>
                        </div>
                        <p class="mb-6 flex-grow text-white/60">
                            {{ plan.description }}
                        </p>
                        <ul class="mb-8 space-y-3 text-sm text-white/60">
                            <li class="flex items-center">
                                <Zap class="mr-2 h-4 w-4 text-gray-500" />
                                <span>{{ plan.limits.dj_tag_limit }} Tag</span>
                            </li>
                            <li class="flex items-center">
                                <Zap class="mr-2 h-4 w-4 text-gray-500" />
                                <span
                                    >{{
                                        plan.limits.dj_tag_version_limit
                                    }}
                                    Versions per Tag</span
                                >
                            </li>
                        </ul>

                        <Button
                            disabled
                            class="w-full cursor-not-allowed bg-white/5 text-white/40"
                        >
                            Coming Soon
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-32 border-t border-white/10 py-12">
                <div
                    class="container mx-auto flex flex-col items-center justify-between gap-6 px-6 md:flex-row"
                >
                    <div class="flex items-center gap-2">
                        <div
                            class="flex h-6 w-6 items-center justify-center rounded-md bg-orange-600"
                        >
                            <AudioLines class="h-3 w-3 fill-white text-white" />
                        </div>
                        <span class="text-sm font-semibold text-white"
                            >DJ Tag Generator</span
                        >
                    </div>
                    <div class="text-sm text-white/40">
                        &copy; {{ year }} DJ Tag Generator. Made for creators.
                        Built by
                        <a
                            href="https://lxst-digital.com"
                            target="_blank"
                            class="underline hover:text-primary"
                        >
                            LXST Digital </a
                        >.
                    </div>
                </div>
            </footer>
        </main>
    </div>
</template>
