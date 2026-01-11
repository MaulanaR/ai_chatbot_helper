<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
            <Link href="/">
                <span class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 tracking-tighter drop-shadow-sm">
                    ChatBot SaaS
                </span>
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/60 backdrop-blur-2xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden sm:rounded-3xl transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
            <slot />
        </div>
    </div>
</template>
