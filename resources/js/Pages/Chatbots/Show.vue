<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {inject} from 'vue';
import GlassCard from '@/Components/GlassCard.vue';
import GlassButton from '@/Components/GlassButton.vue';

const route = inject('route');

defineProps({
    chatbot: {
        type: Object,
        required: true
    },
    embedCode: {
        type: String,
        required: true
    },
    widgetUrl: {
        type: String,
        required: true
    }
});

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    alert('Embed code copied to clipboard!');
};

const openWidget = (url) => {
    window.open(url, '_blank');
};
</script>

<template>
    <Head :title="`Chatbot - ${chatbot.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('chatbots.index')" class="group p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </Link>
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                            {{ chatbot.name }}
                        </h2>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 ml-10">Configuration & Integration Details</p>
                </div>
                
                <div class="flex items-center gap-3 ml-10 sm:ml-0">
                    <Link :href="route('chatbots.edit', chatbot.id)" class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-gray-700 font-medium text-sm border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </Link>
                    <button 
                        @click="openWidget(widgetUrl)" 
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm shadow-lg shadow-indigo-100 transition-all transform hover:-translate-y-0.5"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Preview Widget
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <!-- Chatbot Details -->
                    <GlassCard title="Chatbot Details" class="h-full">
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ chatbot.name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">UUID</label>
                                <div class="mt-1 flex items-center space-x-2">
                                    <code class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-sm font-mono truncate">{{ chatbot.uuid }}</code>
                                    <button 
                                        @click="copyToClipboard(chatbot.uuid)"
                                        class="text-gray-400 hover:text-indigo-600 transition-colors p-1"
                                        title="Copy UUID"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012 2v8a2 2 0 01-2 2h-8a2 2 0 01-2-2v-8a2 2 0 012-2z"></path></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">System Prompt</label>
                                <div class="mt-2 bg-gray-50/50 p-4 rounded-xl border border-gray-100 text-sm text-gray-700 leading-relaxed italic">
                                    "{{ chatbot.system_prompt }}"
                                </div>
                            </div>
                        </div>
                    </GlassCard>

                    <!-- Embed Code -->
                    <GlassCard title="Integration" class="h-full">
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Direct Widget URL</label>
                                <div class="mt-2 flex shadow-sm rounded-xl overflow-hidden">
                                    <input
                                        type="text"
                                        :value="widgetUrl"
                                        readonly
                                        class="block w-full border-0 bg-gray-50 text-gray-600 text-sm focus:ring-0"
                                    />
                                    <button @click="copyToClipboard(widgetUrl)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 font-medium text-sm transition-colors">
                                        Copy
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">HTML Embed Code</label>
                                <div class="mt-2 relative group">
                                    <textarea
                                        :value="embedCode"
                                        readonly
                                        rows="4"
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 text-sm font-mono focus:ring-indigo-500 focus:border-indigo-500 resize-none p-4"
                                    ></textarea>
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="copyToClipboard(embedCode)" class="bg-gray-900/80 hover:bg-gray-900 text-white text-xs px-2 py-1 rounded shadow-sm backdrop-blur">
                                            Copy
                                        </button>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Paste this code anywhere in your website's <code>&lt;body&gt;</code>.</p>
                            </div>
                        </div>
                    </GlassCard>
                </div>

                <!-- Knowledge Base -->
                <GlassCard title="Knowledge Base">
                    <div v-if="chatbot.knowledge_bases.length === 0" class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 11.754 5 10 5s-3.168.477-4.5 1.253m0 13C6.832 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253m0-13V8.25m9 0v-2.247C15.168 5.477 13.754 5 12 5s-3.168.477-4.5 1.253m0 13V8.25"></path></svg>
                        </div>
                        <p class="text-gray-500">No knowledge base added.</p>
                    </div>
                    <div v-else class="space-y-3">
                        <div v-for="kb in chatbot.knowledge_bases" :key="kb.id" class="group flex items-center justify-between p-4 bg-white/50 border border-white/60 rounded-xl hover:shadow-md transition-all duration-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <span v-if="kb.type === 'pdf'" class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </span>
                                    <span v-else class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ kb.type === 'pdf' ? (kb.file_path || 'Unnamed PDF') : 'Text Content' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ kb.content.length }} characters
                                    </p>
                                </div>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase tracking-wide"
                                        :class="kb.type === 'pdf' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-blue-50 text-blue-700 border border-blue-100'">
                                    {{ kb.type }}
                                </span>
                            </div>
                        </div>
                    </div>
                </GlassCard>

                <!-- Live Widget Preview -->
                <GlassCard title="Live Preview">
                    <div class="relative">
                        <div class="absolute top-0 right-0 flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                Live
                            </span>
                            <button 
                                @click="openWidget(widgetUrl)" 
                                class="text-gray-500 hover:text-indigo-600 transition-colors p-1.5 rounded-lg hover:bg-gray-100"
                                title="Open in new tab"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </button>
                        </div>
                        <div class="bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl p-4 mt-8">
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                                <iframe 
                                    :src="widgetUrl" 
                                    class="w-full h-[600px] border-0"
                                    title="Chatbot Widget Preview"
                                    allow="clipboard-write"
                                ></iframe>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-gray-500 text-center">
                            This is a live preview of your chatbot widget. Try sending a message!
                        </p>
                    </div>
                </GlassCard>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
