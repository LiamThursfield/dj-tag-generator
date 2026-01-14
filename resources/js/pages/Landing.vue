<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowRight, AudioLines, Music, Radio, Sparkles } from 'lucide-vue-next';
import { dashboard, login, register } from '@/routes';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const year = new Date().getFullYear();
</script>

<template>
    <Head title="DJ Tag Generator" />

    <!-- Dark Mode Wrapper with Noise Texture Overlay -->
    <div class="min-h-screen bg-[#0a0a0a] font-sans text-white antialiased selection:bg-orange-500/30 selection:text-orange-200">

        <!-- Navigation - Floating Glass Pill -->
        <header class="fixed top-6 z-50 w-full px-4">
            <div class="mx-auto max-w-5xl rounded-md border border-white/10 bg-black/50 px-6 py-3 backdrop-blur-md transition-all duration-300 hover:border-white/20 hover:bg-black/60">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-gradient-to-br from-orange-500 to-orange-700 shadow-[0_0_15px_rgba(249,115,22,0.5)]">
                            <AudioLines class="h-5 w-5 text-white fill-white" />
                        </div>
                        <span class="text-lg font-bold tracking-tight text-white">DJ Tag Gen</span>
                    </div>

                    <nav v-if="canLogin" class="flex gap-4">
                        <template v-if="$page.props.auth.user">
                            <Link :href="dashboard()">
                                <Button variant="ghost" class="text-white/80 hover:text-white hover:bg-white/10">Dashboard</Button>
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="login()">
                                <Button variant="ghost" class="text-white/80 hover:text-white hover:bg-white/10 hidden sm:inline-flex">Log in</Button>
                            </Link>
                            <Link v-if="canRegister" :href="register()">
                                <Button class="rounded-full bg-white text-black hover:bg-gray-200 font-medium">
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
                <div class="absolute left-1/2 top-0 -z-10 h-[500px] w-[500px] -translate-x-1/2 rounded-full bg-orange-600/20 blur-[120px]"></div>

                <div class="mx-auto max-w-3xl text-center">
                    <div class="mb-6 inline-flex items-center rounded-full border border-orange-500/30 bg-orange-500/10 px-3 py-1 text-sm text-orange-300">
                        <Sparkles class="mr-2 h-3.5 w-3.5" />
                        <span>AI-Powered Audio Generation</span>
                    </div>
                    <h1 class="text-5xl font-bold tracking-tighter sm:text-7xl bg-gradient-to-b from-white to-white/60 bg-clip-text text-transparent">
                        Professional DJ drops <br /> in <span class="bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">seconds</span>.
                    </h1>
                    <p class="mt-8 text-lg leading-relaxed text-white/60 text-balance">
                        Generate high-quality, custom DJ tags and drops using advanced AI. <br class="hidden sm:block">Elevate your mixes with professional voiceovers instantly.
                    </p>
                    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Link v-if="canRegister" :href="register()">
                            <Button class="h-12 px-8 bg-primary text-white hover:bg-orange-500 shadow-[0_0_20px_rgba(234,88,12,0.4)] transition-all hover:scale-105 active:scale-95 font-semibold text-base">
                                Start Creating Free <ArrowRight class="ml-2 h-4 w-4" />
                            </Button>
                        </Link>
                        <Link :href="login()">
                            <Button variant="ghost" class="text-white/60 hover:text-white hover:bg-white/5 h-12 px-8">
                                Existing User? Log in
                            </Button>
                        </Link>
                    </div>
                </div>

                <!-- Hero Image with Tilt & Glow -->
                <div class="mt-20 flex justify-center">
                    <div class="max-w-6xl relative rounded-xl bg-black/40 p-2 ring-1 ring-white/10 backdrop-blur-xl transition-all duration-700 hover:rotate-0 sm:rotate-x-12 sm:hover:scale-[1.02] shadow-2xl shadow-orange-900/20 group">
                        <div class="absolute -inset-1 rounded-xl bg-gradient-to-r from-primary to-pink-500 opacity-20 blur-xl group-hover:opacity-40 transition-opacity duration-500"></div>
                        <img src="/images/hero-tag-create.png" alt="App screenshot" class="relative z-10 rounded-lg shadow-2xl w-full" />

                        <!-- Floating Glass Card overlay example -->
                        <div class="absolute -right-8 -bottom-8 hidden lg:block animate-bounce-slow z-10">
                             <div class="rounded-2xl border border-white/10 bg-black/60 p-4 backdrop-blur-md shadow-xl max-w-[200px]">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary-foreground">
                                        <Music class="h-4 w-4" />
                                    </div>
                                    <div>
                                        <div class="text-xs font-medium text-primary-foreground">Generation Complete</div>
                                        <div class="text-[10px] text-primary-foreground/50">Just now</div>
                                    </div>
                                </div>
                                <div class="h-2 w-full rounded-full bg-white/10 overflow-hidden">
                                    <div class="h-full w-full bg-primary/80 rounded-full"></div>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Grid Features Section -->
            <div class="mx-auto mt-32 max-w-6xl">
                <div class="mx-auto max-w-2xl text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Everything you need</h2>
                    <p class="mt-4 text-white/60">Powerful tools to give your mixes a professional identity.</p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3 md:grid-rows-2">
                    <!-- Large Card -->
                    <div class="group relative col-span-1 md:col-span-2 md:row-span-2 overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-8 transition-all hover:bg-white/10 hover:border-white/20">
                        <div class="absolute top-0 right-0 -m-16 h-64 w-64 rounded-full bg-orange-600/20 blur-[80px]"></div>
                        <div class="relative z-10">
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-500/20 text-orange-400">
                                <Radio class="h-6 w-6" />
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Instant Voice Synthesis</h3>
                            <p class="text-white/60 max-w-md">Type your script, choose from dozens of professional DJ voices, and generate studio-quality audio in seconds. No recording equipment needed.</p>
                        </div>
                    </div>

                    <!-- Small Card 1 -->
                    <div class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-8 transition-all hover:bg-white/10 hover:border-white/20">
                        <h3 class="text-lg font-bold text-white mb-2">Wait-free Generation</h3>
                        <p class="text-sm text-white/60">Lightning fast processing means no more waiting for expensive voice actors.</p>
                    </div>

                    <!-- Small Card 2 with Gradient -->
                    <div class="group relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-orange-900/40 to-black p-8 transition-all hover:border-orange-500/30">
                        <div class="absolute inset-0 bg-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <h3 class="text-lg font-bold text-white mb-2">Effect Processing</h3>
                        <p class="text-sm text-white/60">Add reverb, delay, and stutter effects automatically.</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-32 border-t border-white/10 py-12">
                <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-6 rounded-md bg-orange-600 flex items-center justify-center">
                            <AudioLines class="h-3 w-3 text-white fill-white" />
                        </div>
                        <span class="text-sm font-semibold text-white">DJ Tag Gen</span>
                    </div>
                    <div class="text-sm text-white/40">
                        &copy; {{ year  }} DJ Tag Generator. Made for creators. Built by <a href="https://lxst-digital.com" target="_blank" class="underline hover:text-white">LXST Digital</a>.
                    </div>
                </div>
            </footer>
        </main>
    </div>
</template>
