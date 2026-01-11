<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { inject } from 'vue';
import GlassCard from '@/Components/GlassCard.vue';

const route = inject('route');

defineProps({
    chatbots: {
        type: Array,
        required: true
    }
});

const deleteChatbot = (id) => {
    if (confirm('Are you sure you want to delete this chatbot?')) {
        router.delete(route('chatbots.destroy', id));
    }
};
</script>

<template>
    <Head title="My Chatbots" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        My Chatbots
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">Manage and monitor your AI assistants</p>
                </div>
                <Link 
                    :href="route('chatbots.create')" 
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm shadow-lg shadow-indigo-200 transition-all duration-200 hover:shadow-indigo-300 transform hover:-translate-y-0.5"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Chatbot
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <template v-if="chatbots.length === 0">
                <GlassCard class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 mb-6 group">
                        <svg class="w-10 h-10 text-indigo-500 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No chatbots yet</h3>
                    <p class="text-gray-500 mb-8 max-w-sm mx-auto">It looks like you haven't created any AI assistants yet. Create one to get started.</p>
                    <Link :href="route('chatbots.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-2xl font-bold shadow-lg hover:shadow-indigo-500/30 transition-all duration-300">
                        Create Your First Chatbot
                    </Link>
                </GlassCard>
            </template>

            <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <GlassCard v-for="chatbot in chatbots" :key="chatbot.id" class="group hover:bg-white/50 transition-colors relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                         <button @click="deleteChatbot(chatbot.id)" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors" title="Delete Chatbot">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4 mb-4">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            {{ chatbot.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ chatbot.name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                Active
                            </span>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-6 line-clamp-2">
                        {{ chatbot.system_prompt || 'No system prompt defined.' }}
                    </p>

                    <div class="flex items-center justify-between text-sm text-gray-500 border-t border-gray-100 pt-4 mt-auto">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            {{ chatbot.knowledge_bases.length }} Docs
                        </div>
                        <div class="flex space-x-3">
                            <Link :href="route('chatbots.show', chatbot.id)" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">View</Link>
                            <span class="text-gray-300">|</span>
                            <Link :href="route('chatbots.edit', chatbot.id)" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Edit</Link>
                        </div>
                    </div>
                </GlassCard>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
