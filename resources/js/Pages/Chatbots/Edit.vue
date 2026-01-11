<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject } from 'vue';
import GlassCard from '@/Components/GlassCard.vue';
import GlassButton from '@/Components/GlassButton.vue';
import GlassInput from '@/Components/GlassInput.vue';

const props = defineProps({
    chatbot: {
        type: Object,
        required: true
    },
    flash: Object,
});

const route = inject('route');

const form = useForm({
    name: props.chatbot.name,
    system_prompt: props.chatbot.system_prompt || '',
});

const submit = () => {
    form.patch(route('chatbots.update', props.chatbot.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Edit - ${chatbot.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('chatbots.show', chatbot.id)" class="group p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </Link>
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                            Edit Chatbot
                        </h2>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 ml-10">Update chatbot settings and configuration</p>
                </div>
            </div>
        </template>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.error" class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 shadow-sm animate-pulse">
                {{ $page.props.flash.error }}
            </div>
            <div v-if="$page.props.flash?.success" class="mb-4 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 shadow-sm">
                {{ $page.props.flash.success }}
            </div>

            <GlassCard>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Chatbot Name</label>
                        <GlassInput
                            type="text"
                            id="name"
                            v-model="form.name"
                            placeholder="e.g. Customer Support Bot"
                            required
                        />
                        <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                    </div>

                    <div>
                        <label for="system_prompt" class="block text-sm font-medium text-gray-700 mb-1">System Prompt</label>
                        <textarea
                            id="system_prompt"
                            v-model="form.system_prompt"
                            rows="6"
                            class="w-full px-5 py-3 rounded-2xl border-0 bg-white/40 backdrop-blur-md shadow-inner text-gray-900 focus:ring-2 focus:ring-blue-500/50 focus:bg-white/60 transition-all duration-300 placeholder-gray-500/70"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">Define how the chatbot should behave and answer.</p>
                        <div v-if="form.errors.system_prompt" class="text-red-500 text-xs mt-1">{{ form.errors.system_prompt }}</div>
                    </div>

                    <!-- Knowledge Base Info (Read-Only) -->
                    <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Knowledge Base
                        </h3>
                        
                        <div v-if="chatbot.knowledge_bases?.length > 0" class="space-y-3">
                            <div v-for="kb in chatbot.knowledge_bases" :key="kb.id" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span v-if="kb.type === 'pdf'" class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </span>
                                    <span v-else class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ kb.type === 'pdf' ? (kb.file_path || 'PDF Document') : 'Text Content' }}</p>
                                        <p class="text-xs text-gray-500">{{ kb.content?.length || 0 }} characters</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase tracking-wide"
                                    :class="kb.type === 'pdf' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700'">
                                    {{ kb.type }}
                                </span>
                            </div>
                        </div>
                        <div v-else class="text-center py-4 text-gray-500 text-sm">
                            No knowledge base found.
                        </div>
                        <p class="text-xs text-gray-400 mt-3">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            To update the knowledge base, please delete this chatbot and create a new one.
                        </p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200/50">
                        <Link :href="route('chatbots.show', chatbot.id)" class="px-6 py-2.5 rounded-xl text-gray-700 bg-white/50 hover:bg-white/80 transition-colors font-medium">
                            Cancel
                        </Link>
                        <GlassButton
                            type="submit"
                            :disabled="form.processing"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </GlassButton>
                    </div>
                </form>
            </GlassCard>
        </div>
    </AuthenticatedLayout>
</template>
