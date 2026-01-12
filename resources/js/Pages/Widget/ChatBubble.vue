<script setup>
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

// State
const isOpen = ref(false);
const messages = ref([]);
const newMessage = ref('');
const isLoading = ref(false);
const messagesContainer = ref(null);
const messageInput = ref(null);

// Toggle chat box
const toggleChat = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        nextTick(() => {
            messageInput.value?.focus();
            scrollToBottom();
        });
    }
};

// Close chat box
const closeChat = () => {
    isOpen.value = false;
};

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
        messageInput.value?.focus();
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
});
</script>

<template>
    <Head :title="chatbot.name" />

    <!-- Floating Chat Widget Container -->
    <div class="chat-widget-container">
        <!-- Chat Box -->
        <Transition name="chat-box">
            <div v-show="isOpen" class="chat-box">
                <!-- Header -->
                <div class="chat-header">
                    <div class="header-left">
                        <div class="avatar">
                            {{ chatbot.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="header-info">
                            <h1 class="header-title">{{ chatbot.name }}</h1>
                            <p class="header-subtitle">
                                <span class="online-dot"></span>
                                Online
                            </p>
                        </div>
                    </div>
                    <button @click="closeChat" class="close-btn" aria-label="Close chat">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Messages Container -->
                <div ref="messagesContainer" class="messages-container">
                    <div
                        v-for="message in messages"
                        :key="message.id"
                        class="message-wrapper"
                        :class="message.sender === 'user' ? 'message-user' : 'message-bot'"
                    >
                        <div class="message-bubble" :class="message.sender === 'user' ? 'bubble-user' : 'bubble-bot'">
                            <div 
                                v-if="message.sender === 'bot'"
                                class="message-content markdown-content"
                                v-html="md.render(message.content)"
                            ></div>
                            <p v-else class="message-content">{{ message.content }}</p>
                            <p class="message-time">
                                {{ new Date(message.timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                            </p>
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div v-if="isLoading" class="message-wrapper message-bot">
                        <div class="message-bubble bubble-bot">
                            <div class="typing-indicator">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="input-area">
                    <form @submit.prevent="sendMessage" class="input-form">
                        <input
                            ref="messageInput"
                            v-model="newMessage"
                            type="text"
                            placeholder="Type your message..."
                            :disabled="isLoading"
                            class="message-input"
                        />
                        <button
                            type="submit"
                            :disabled="isLoading || !newMessage.trim()"
                            class="send-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Powered by Footer -->
                <div class="powered-by">
                    Powered by <span class="brand">ChatBot</span>
                </div>
            </div>
        </Transition>

        <!-- Floating Button -->
        <button 
            @click="toggleChat" 
            class="floating-btn"
            :class="{ 'is-open': isOpen }"
            aria-label="Toggle chat"
        >
            <Transition name="icon-rotate" mode="out-in">
                <svg v-if="!isOpen" key="chat" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <svg v-else key="close" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </Transition>
            
            <!-- Notification badge (optional) -->
            <span v-if="!isOpen && messages.length > 1" class="notification-badge">
                {{ messages.length - 1 }}
            </span>
        </button>
    </div>
</template>

<style scoped>
/* CSS Variables for theming */
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --primary-light: rgba(99, 102, 241, 0.1);
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --bg-primary: #ffffff;
    --bg-secondary: #f3f4f6;
    --border-color: #e5e7eb;
    --shadow-color: rgba(0, 0, 0, 0.1);
}

/* Container */
.chat-widget-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Floating Button */
.floating-btn {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 
        0 8px 24px rgba(99, 102, 241, 0.4),
        0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.floating-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.floating-btn:hover {
    transform: scale(1.05);
    box-shadow: 
        0 12px 32px rgba(99, 102, 241, 0.5),
        0 6px 12px rgba(0, 0, 0, 0.15);
}

.floating-btn:hover::before {
    opacity: 1;
}

.floating-btn:active {
    transform: scale(0.95);
}

.floating-btn.is-open {
    background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
    box-shadow: 
        0 8px 24px rgba(239, 68, 68, 0.4),
        0 4px 8px rgba(0, 0, 0, 0.1);
}

.floating-btn svg {
    position: relative;
    z-index: 1;
}

/* Notification Badge */
.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 22px;
    height: 22px;
    background: #ef4444;
    border-radius: 50%;
    color: white;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    animation: pulse-badge 2s infinite;
}

@keyframes pulse-badge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Chat Box */
.chat-box {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 380px;
    max-height: 600px;
    background: white;
    border-radius: 20px;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 12px 24px -8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Header */
.chat-header {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.header-info {
    display: flex;
    flex-direction: column;
}

.header-title {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

.header-subtitle {
    font-size: 12px;
    margin: 0;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 6px;
}

.online-dot {
    width: 8px;
    height: 8px;
    background: #22c55e;
    border-radius: 50%;
    display: inline-block;
    animation: pulse-online 2s infinite;
}

@keyframes pulse-online {
    0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
    50% { opacity: 0.8; box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
}

.close-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.2s ease;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: rotate(90deg);
}

/* Messages Container */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 350px;
    background: linear-gradient(180deg, #f9fafb 0%, #ffffff 100%);
}

.messages-container::-webkit-scrollbar {
    width: 6px;
}

.messages-container::-webkit-scrollbar-track {
    background: transparent;
}

.messages-container::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Message Wrapper */
.message-wrapper {
    display: flex;
    animation: message-in 0.3s ease-out;
}

@keyframes message-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-user {
    justify-content: flex-end;
}

.message-bot {
    justify-content: flex-start;
}

/* Message Bubble */
.message-bubble {
    max-width: 80%;
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
}

.bubble-user {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    border-bottom-right-radius: 6px;
}

.bubble-bot {
    background: white;
    color: #374151;
    border-bottom-left-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #f3f4f6;
}

.message-content {
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
    word-wrap: break-word;
}

.message-time {
    font-size: 10px;
    opacity: 0.7;
    margin-top: 4px;
    text-align: right;
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 4px 0;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #6366f1;
    border-radius: 50%;
    animation: typing-bounce 1.4s ease-in-out infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing-bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-8px); }
}

/* Input Area */
.input-area {
    padding: 16px;
    background: white;
    border-top: 1px solid #f3f4f6;
}

.input-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.message-input {
    flex: 1;
    padding: 12px 18px;
    border: 2px solid #e5e7eb;
    border-radius: 24px;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
    background: #f9fafb;
}

.message-input:focus {
    border-color: #6366f1;
    background: white;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.message-input::placeholder {
    color: #9ca3af;
}

.message-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.send-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.send-btn:hover:not(:disabled) {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

.send-btn:active:not(:disabled) {
    transform: scale(0.95);
}

.send-btn:disabled {
    background: #d1d5db;
    cursor: not-allowed;
    box-shadow: none;
}

.send-btn svg {
    transform: rotate(90deg);
}

/* Powered By Footer */
.powered-by {
    text-align: center;
    padding: 10px;
    font-size: 11px;
    color: #9ca3af;
    background: #f9fafb;
    border-top: 1px solid #f3f4f6;
}

.powered-by .brand {
    color: #6366f1;
    font-weight: 600;
}

/* Transitions */
.chat-box-enter-active {
    animation: chat-box-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.chat-box-leave-active {
    animation: chat-box-out 0.25s ease-in;
}

@keyframes chat-box-in {
    0% {
        opacity: 0;
        transform: scale(0.8) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes chat-box-out {
    0% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
    100% {
        opacity: 0;
        transform: scale(0.8) translateY(20px);
    }
}

.icon-rotate-enter-active,
.icon-rotate-leave-active {
    transition: all 0.25s ease;
}

.icon-rotate-enter-from {
    opacity: 0;
    transform: scale(0.5) rotate(-180deg);
}

.icon-rotate-leave-to {
    opacity: 0;
    transform: scale(0.5) rotate(180deg);
}

/* Markdown Content Styles */
.markdown-content :deep(p) {
    margin-bottom: 0.5rem;
}

.markdown-content :deep(p:last-child) {
    margin-bottom: 0;
}

.markdown-content :deep(ul), 
.markdown-content :deep(ol) {
    margin-bottom: 0.5rem;
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
    background-color: rgba(0, 0, 0, 0.08);
    padding: 0.125rem 0.375rem;
    border-radius: 4px;
    font-family: 'SF Mono', Consolas, monospace;
    font-size: 13px;
}

.markdown-content :deep(pre) {
    background-color: #1f2937;
    color: #e5e7eb;
    padding: 12px;
    border-radius: 8px;
    overflow-x: auto;
    margin: 8px 0;
}

.markdown-content :deep(a) {
    color: #6366f1;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 480px) {
    .chat-widget-container {
        bottom: 16px;
        right: 16px;
    }
    
    .chat-box {
        width: calc(100vw - 32px);
        max-width: 360px;
        max-height: 500px;
        bottom: 76px;
    }
    
    .floating-btn {
        width: 56px;
        height: 56px;
    }
    
    .messages-container {
        max-height: 280px;
    }
}
</style>
