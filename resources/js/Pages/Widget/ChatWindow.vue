<script setup>
import WidgetLayout from '@/Layouts/WidgetLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, inject, computed, nextTick } from 'vue';
import MarkdownIt from 'markdown-it';

const md = new MarkdownIt({
    html: true,
    linkify: true,
    typographer: true
});


const route = inject('route');

const props = defineProps({
    chatbot: {
        type: Object,
        required: true
    }
});

const messages = ref([]);
const newMessage = ref('');
const isLoading = ref(false);
const messagesContainer = ref(null);
const messageInput = ref(null);


const sendMessage = async () => {
    if (!newMessage.value.trim() || isLoading.value) return;

    const userMessage = {
        id: Date.now(),
        content: newMessage.value,
        sender: 'user',
        timestamp: new Date().toISOString()
    };

    messages.value.push(userMessage);
    const messageContent = newMessage.value;
    newMessage.value = '';
    messageInput.value?.focus();
    isLoading.value = true;

    try {
        // Use fetch for API call to Inertia
        const response = await fetch(route('widget.send', props.chatbot.uuid), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                message: messageContent
            })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        
        const botMessage = {
            id: Date.now() + 1,
            content: data.reply || 'Sorry, I could not process your message.',
            sender: 'bot',
            timestamp: new Date().toISOString()
        };

        messages.value.push(botMessage);
        scrollToBottom();
    } catch (error) {
        console.error('Error sending message:', error);
        const errorMessage = {
            id: Date.now() + 1,
            content: 'Sorry, I encountered an error. Please try again.',
            sender: 'bot',
            timestamp: new Date().toISOString()
        };
        messages.value.push(errorMessage);
        
    } finally {
        isLoading.value = false;
        scrollToBottom();
        nextTick(() => {
            messageInput.value?.focus();
        });
    }
};

const scrollToBottom = () => {
    if (messagesContainer.value) {
        setTimeout(() => {
            messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
        }, 100);
    }
};

onMounted(() => {
    // Add welcome message
    messages.value.push({
        id: 1,
        content: `Hello! I'm ${props.chatbot.name}. How can I help you today?`,
        sender: 'bot',
        timestamp: new Date().toISOString()
    });
    
    // Focus the input
    setTimeout(() => {
        messageInput.value?.focus();
    }, 100);
});
</script>

<template>
    <Head :title="chatbot.name" />

    <WidgetLayout>
        <div class="flex flex-col h-full bg-transparent w-full max-w-2xl mx-auto shadow-2xl rounded-2xl overflow-hidden backdrop-blur-3xl bg-white/60 border border-white/50">
            <!-- Header -->
            <div class="bg-indigo-600/90 text-white p-4 backdrop-blur-md flex items-center shadow-md relative z-20">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-3 font-bold text-lg shadow-inner border border-white/30">
                    {{ chatbot.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                    <h1 class="text-lg font-bold leading-tight shadow-black/20 text-shadow">{{ chatbot.name }}</h1>
                    <p class="text-indigo-100 text-xs font-medium">✨ Ask me anything!</p>
                </div>
            </div>

            <!-- Messages Container -->
            <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4 scroll-smooth">
                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="flex"
                    :class="message.sender === 'user' ? 'justify-end' : 'justify-start'"
                >
                    <div
                        class="max-w-[80%] px-5 py-3 rounded-2xl shadow-sm backdrop-blur-sm transition-all duration-300"
                        :class="message.sender === 'user' 
                            ? 'bg-indigo-600/90 text-white rounded-br-none border border-indigo-500/50 shadow-indigo-500/10' 
                            : 'bg-white/80 text-gray-800 rounded-bl-none border border-white/60 shadow-gray-200/50'"
                    >
                        <div 
                            v-if="message.sender === 'bot'"
                            class="text-sm leading-relaxed prose prose-sm max-w-none markdown-content"
                            v-html="md.render(message.content)"
                        ></div>
                        <p v-else class="text-sm leading-relaxed">{{ message.content }}</p>

                        <p class="text-[10px] mt-1 opacity-70 text-right">
                            {{ new Date(message.timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                        </p>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div v-if="isLoading" class="flex justify-start">
                    <div class="bg-white/80 text-gray-800 rounded-2xl rounded-bl-none px-4 py-3 shadow-sm border border-white/60">
                        <div class="flex space-x-1.5 item-center h-full">
                            <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white/40 backdrop-blur-md border-t border-white/50 relative z-20">
                <form @submit.prevent="sendMessage" class="flex space-x-2 relative group">
                    <input
                        ref="messageInput"
                        v-model="newMessage"
                        type="text"
                        placeholder="Type your message..."
                        :disabled="isLoading"
                        class="flex-1 pl-5 pr-4 py-3 bg-white/70 border border-white/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:bg-white/90 placeholder-gray-400 text-sm shadow-inner transition-all duration-300"
                    />
                    <button
                        type="submit"
                        :disabled="isLoading || !newMessage.trim()"
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-2 rounded-xl font-semibold shadow-lg shadow-indigo-500/30 transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </WidgetLayout>
</template>

<style scoped>
.markdown-content :deep(p) {
    margin-bottom: 0.75rem;
}
.markdown-content :deep(p:last-child) {
    margin-bottom: 0;
}
.markdown-content :deep(ul), .markdown-content :deep(ol) {
    margin-bottom: 0.75rem;
    padding-left: 1.25rem;
}
.markdown-content :deep(ul) {
    list-style-type: disc;
}
.markdown-content :deep(ol) {
    list-style-type: decimal;
}
.markdown-content :deep(li) {
    margin-bottom: 0.25rem;
}
.markdown-content :deep(strong) {
    font-weight: 700;
}
.markdown-content :deep(em) {
    font-style: italic;
}
.markdown-content :deep(code) {
    background-color: rgba(0, 0, 0, 0.05);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: monospace;
}
.markdown-content :deep(pre) {
    background-color: #f4f4f4;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin-bottom: 0.75rem;
}
</style>

