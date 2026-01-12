<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { inject, computed } from 'vue';
import GlassCard from '@/Components/GlassCard.vue';

const route = inject('route');

const props = defineProps({
    stats: {
        type: Object,
        required: true
    },
    messagesPerDay: {
        type: Object,
        default: () => ({})
    },
    chatbotStats: {
        type: Array,
        default: () => []
    },
    recentSessions: {
        type: Array,
        default: () => []
    }
});

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
    if (!dateString) return 'No activity';
    return new Date(dateString).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Truncate text
const truncate = (text, length = 60) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};
</script>

<template>
    <Head title="Analytics Overview" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Analytics Overview
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">Monitor performance across all your chatbots</p>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <GlassCard class="text-center py-6">
                    <div class="text-4xl font-bold text-violet-600">{{ stats.totalChatbots }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Chatbots</div>
                </GlassCard>
                
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
                
                <GlassCard class="text-center py-6 col-span-2 lg:col-span-1">
                    <div class="text-4xl font-bold text-amber-600">{{ stats.avgMessagesPerSession }}</div>
                    <div class="text-sm text-gray-500 mt-1">Avg per Session</div>
                </GlassCard>
            </div>

            <!-- Messages Chart -->
            <GlassCard title="Messages Activity (Last 30 Days)">
                <div v-if="stats.totalMessages === 0" class="text-center py-12 text-gray-500">
                    <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <p>No message data yet. Start chatting to see analytics!</p>
                </div>
                <template v-else>
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
                </template>
            </GlassCard>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Chatbot Performance -->
                <GlassCard title="Chatbot Performance">
                    <div v-if="chatbotStats.length === 0" class="text-center py-8 text-gray-500">
                        <p>No chatbots created yet.</p>
                        <Link :href="route('chatbots.create')" class="inline-flex items-center mt-3 text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                            Create your first chatbot →
                        </Link>
                    </div>
                    <div v-else class="space-y-3">
                        <Link v-for="bot in chatbotStats" :key="bot.id" 
                            :href="route('chatbots.analytics', bot.id)"
                            class="flex items-center justify-between p-4 bg-white/50 rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ bot.name.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ bot.name }}</p>
                                    <p class="text-xs text-gray-500">{{ formatTime(bot.lastActivity) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="text-center">
                                    <p class="font-bold text-indigo-600">{{ bot.sessions }}</p>
                                    <p class="text-xs text-gray-400">sessions</p>
                                </div>
                                <div class="text-center">
                                    <p class="font-bold text-emerald-600">{{ bot.messages }}</p>
                                    <p class="text-xs text-gray-400">messages</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </Link>
                    </div>
                </GlassCard>

                <!-- Recent Conversations -->
                <GlassCard title="Recent Conversations">
                    <div v-if="recentSessions.length === 0" class="text-center py-8 text-gray-500">
                        <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <p>No conversations yet.</p>
                    </div>
                    <div v-else class="space-y-3 max-h-96 overflow-y-auto">
                        <div v-for="session in recentSessions" :key="session.id"
                            class="p-4 bg-white/50 rounded-xl border border-gray-100 hover:border-indigo-100 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                                        {{ session.chatbot?.name || 'Unknown' }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ formatTime(session.created_at) }}</span>
                                </div>
                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                    {{ session.messages?.length || 0 }} msgs
                                </span>
                            </div>
                            <div v-if="session.messages?.length > 0" class="space-y-1">
                                <div v-for="msg in session.messages.slice(0, 2)" :key="msg.id" class="text-sm">
                                    <span :class="msg.role === 'user' ? 'text-indigo-600 font-medium' : 'text-gray-500'">
                                        {{ msg.role === 'user' ? 'Q:' : 'A:' }}
                                    </span>
                                    {{ truncate(msg.content) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </GlassCard>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
