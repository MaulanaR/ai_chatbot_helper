<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-[#F5F5F7] text-gray-900 font-sans relative overflow-hidden selection:bg-indigo-500/30">
        <!-- Global Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-purple-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-[50%] h-[50%] bg-pink-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <Head :title="title" />
        <div class="h-screen flex flex-col relative z-10">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
    }
}
</script>
