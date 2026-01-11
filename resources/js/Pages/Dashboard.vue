<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { inject } from 'vue';
import GlassCard from '@/Components/GlassCard.vue';
import GlassButton from '@/Components/GlassButton.vue';

const route = inject('route');
const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                Dashboard
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <GlassCard class="mb-8 overflow-hidden relative">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
                <div class="absolute top-0 left-0 -mt-4 -ml-4 w-24 h-24 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
                
                <div class="text-center relative z-10">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">
                        Welcome back, {{ auth.user.name }}!
                    </h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Ready to manage your AI assistants? Create new chatbots, upload knowledge bases, and deploy them to your site.
                    </p>
                </div>
            </GlassCard>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Quick Stats -->
                <GlassCard class="hover:bg-white/40 transition-colors cursor-pointer group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100/50 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Your Chatbots</h3>
                            <p class="text-sm text-gray-500">Manage your fleet</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <Link :href="route('chatbots.index')" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors group-hover:translate-x-1 duration-300">
                            View All <span class="ml-1">&rarr;</span>
                        </Link>
                    </div>
                </GlassCard>

                <!-- Quick Actions -->
                <GlassCard class="hover:bg-white/40 transition-colors cursor-pointer group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100/50 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">New Assistant</h3>
                            <p class="text-sm text-gray-500">Build something new</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <Link :href="route('chatbots.create')" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition-colors group-hover:translate-x-1 duration-300">
                            Create Now <span class="ml-1">&rarr;</span>
                        </Link>
                    </div>
                </GlassCard>

                <!-- Documentation -->
                <GlassCard class="hover:bg-white/40 transition-colors cursor-pointer group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100/50 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 11.754 5 10 5s-3.168.477-4.5 1.253m0 13C6.832 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253m0-13V8.25m9 0v-2.247C15.168 5.477 13.754 5 12 5s-3.168.477-4.5 1.253m0 13V8.25"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Documentation</h3>
                            <p class="text-sm text-gray-500">Learn the platform</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700 transition-colors group-hover:translate-x-1 duration-300">
                            Read Docs <span class="ml-1">&rarr;</span>
                        </a>
                    </div>
                </GlassCard>
            </div>

            <!-- Recent Activity / Quick Start -->
            <div class="mt-8">
                <GlassCard>
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Quick Start Guide
                    </h3>
                    <div class="relative">
                        <!-- Line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                        <div class="space-y-8 relative">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 relative z-10">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center">
                                        <span class="text-blue-600 text-sm font-bold">1</span>
                                    </div>
                                </div>
                                <div class="ml-4 pt-1">
                                    <p class="text-base font-semibold text-gray-900">Create a new chatbot</p>
                                    <p class="text-sm text-gray-500 mt-1">Give it a unique identity and personality by customizing its system prompt.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 relative z-10">
                                    <div class="h-8 w-8 rounded-full bg-purple-100 border-2 border-white shadow-sm flex items-center justify-center">
                                        <span class="text-purple-600 text-sm font-bold">2</span>
                                    </div>
                                </div>
                                <div class="ml-4 pt-1">
                                    <p class="text-base font-semibold text-gray-900">Add knowledge base</p>
                                    <p class="text-sm text-gray-500 mt-1">Upload your PDF documents or raw text to train your chatbot on your specific data.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 relative z-10">
                                    <div class="h-8 w-8 rounded-full bg-green-100 border-2 border-white shadow-sm flex items-center justify-center">
                                        <span class="text-green-600 text-sm font-bold">3</span>
                                    </div>
                                </div>
                                <div class="ml-4 pt-1">
                                    <p class="text-base font-semibold text-gray-900">Deploy & Embed</p>
                                    <p class="text-sm text-gray-500 mt-1">Get the unique iframe code and paste it into your website to go live.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </GlassCard>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
