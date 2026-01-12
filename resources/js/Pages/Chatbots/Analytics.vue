<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { inject, computed } from 'vue';
import GlassCard from '@/Components/GlassCard.vue';
import { marked } from 'marked';

const route = inject('route');

// Configure marked for safe rendering
marked.setOptions({
    breaks: true,
    gfm: true,
});

const props = defineProps({
    chatbot: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        required: true
    },
    messagesPerDay: {
        type: Object,
        default: () => ({})
    },
    recentSessions: {
        type: Array,
        default: () => []
    },
    topQuestions: {
        type: Array,
        default: () => []
    }
});

// Render markdown to HTML
const renderMarkdown = (text) => {
    if (!text) return '';
    return marked.parse(text);
};

// Generate chart data for the last 30 days
const chartData = computed(() => {
    const days = [];
    const counts = [];
    
    for (let i = 29; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toISOString().split('T')[0];
        days.push(date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
        counts.push(props.messagesPerDay[dateStr] || 0);
    }
    
    return { days, counts };
});

// Calculate max for chart scaling
const maxMessages = computed(() => {
    return Math.max(...chartData.value.counts, 1);
});

// Format timestamp
const formatTime = (dateString) => {
    return new Date(dateString).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Truncate text
const truncate = (text, length = 100) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};
</script>

<template>
    <Head :title="`Analytics - ${chatbot.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('chatbots.show', chatbot.id)" class="group p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </Link>
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                            Analytics
                        </h2>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 ml-10">{{ chatbot.name }} - Performance & Conversations</p>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <GlassCard class="text-center py-6">
                    <div class="text-4xl font-bold text-indigo-600">{{ stats.totalSessions }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Sessions</div>
                </GlassCard>
                
                <GlassCard class="text-center py-6">
                    <div class="text-4xl font-bold text-emerald-600">{{ stats.totalMessages }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Messages</div>
                </GlassCard>
                
                <GlassCard class="text-center py-6">
                    <div class="text-4xl font-bold text-blue-600">{{ stats.userMessages }}</div>
                    <div class="text-sm text-gray-500 mt-1">User Messages</div>
                </GlassCard>
                
                <GlassCard class="text-center py-6">
                    <div class="text-4xl font-bold text-purple-600">{{ stats.assistantMessages }}</div>
                    <div class="text-sm text-gray-500 mt-1">Bot Responses</div>
                </GlassCard>
                
                <GlassCard class="text-center py-6 col-span-2 lg:col-span-1">
                    <div class="text-4xl font-bold text-amber-600">{{ stats.avgMessagesPerSession }}</div>
                    <div class="text-sm text-gray-500 mt-1">Avg per Session</div>
                </GlassCard>
            </div>

            <!-- Messages Chart -->
            <GlassCard title="Messages (Last 30 Days)">
                <div class="h-48 flex items-end gap-1 px-2">
                    <div 
                        v-for="(count, index) in chartData.counts" 
                        :key="index"
                        class="flex-1 bg-gradient-to-t from-indigo-500 to-indigo-400 rounded-t transition-all duration-300 hover:from-indigo-600 hover:to-indigo-500 relative group cursor-pointer"
                        :style="{ height: `${Math.max((count / maxMessages) * 100, 2)}%` }"
                    >
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                            {{ chartData.days[index] }}: {{ count }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-2 px-2">
                    <span>{{ chartData.days[0] }}</span>
                    <span>{{ chartData.days[14] }}</span>
                    <span>{{ chartData.days[29] }}</span>
                </div>
            </GlassCard>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Top Questions -->
                <GlassCard title="Top Questions">
                    <div v-if="topQuestions.length === 0" class="text-center py-8 text-gray-500">
                        No questions recorded yet.
                    </div>
                    <div v-else class="space-y-3">
                        <div v-for="(question, index) in topQuestions" :key="index" 
                            class="flex items-start gap-3 p-3 bg-white/50 rounded-xl border border-gray-100 hover:border-indigo-100 transition-colors">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center">
                                {{ index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 break-words">{{ truncate(question.content, 150) }}</p>
                            </div>
                            <span class="flex-shrink-0 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                {{ question.count }}x
                            </span>
                        </div>
                    </div>
                </GlassCard>

                <!-- Recent Sessions Summary -->
                <GlassCard title="Recent Sessions">
                    <div v-if="recentSessions.length === 0" class="text-center py-8 text-gray-500">
                        No sessions recorded yet.
                    </div>
                    <div v-else class="space-y-3 max-h-96 overflow-y-auto">
                        <div v-for="session in recentSessions" :key="session.id"
                            class="p-3 bg-white/50 rounded-xl border border-gray-100 hover:border-indigo-100 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-500">{{ formatTime(session.created_at) }}</span>
                                <span class="text-xs px-2 py-0.5 bg-indigo-100 text-indigo-600 rounded-full font-medium">
                                    {{ session.messages?.length || 0 }} msgs
                                </span>
                            </div>
                            <div v-if="session.messages?.length > 0" class="text-sm text-gray-700">
                                <span class="font-medium text-indigo-600">Q:</span> 
                                {{ truncate(session.messages[0]?.content, 80) }}
                            </div>
                        </div>
                    </div>
                </GlassCard>
            </div>

            <!-- Conversation History -->
            <GlassCard title="Conversation History">
                <div v-if="recentSessions.length === 0" class="text-center py-12 text-gray-500">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <p class="text-lg font-medium">No conversations yet</p>
                    <p class="text-sm mt-1">Conversations will appear here once users start chatting with your bot.</p>
                </div>
                
                <div v-else class="space-y-6">
                    <div v-for="session in recentSessions.slice(0, 10)" :key="session.id" class="border border-gray-100 rounded-2xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                    V
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Visitor Session</p>
                                    <p class="text-xs text-gray-500">{{ session.visitor_ip || 'Unknown IP' }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ formatTime(session.created_at) }}</span>
                        </div>
                        
                        <div class="p-4 space-y-3 max-h-80 overflow-y-auto bg-white">
                            <div v-for="message in session.messages" :key="message.id" 
                                class="flex gap-3"
                                :class="message.role === 'user' ? 'justify-end' : 'justify-start'">
                                <div 
                                    class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm"
                                    :class="message.role === 'user' 
                                        ? 'bg-indigo-600 text-white rounded-br-md' 
                                        : 'bg-gray-100 text-gray-800 rounded-bl-md'">
                                    <div 
                                        class="prose-chat max-w-none break-words"
                                        :class="message.role === 'user' ? 'prose-invert' : ''"
                                        v-html="renderMarkdown(message.content)">
                                    </div>
                                    <p class="text-xs mt-1 opacity-70">{{ new Date(message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </GlassCard>
        </div>
    </AuthenticatedLayout>
</template>
