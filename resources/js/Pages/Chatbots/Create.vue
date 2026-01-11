<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject } from 'vue';
import GlassCard from '@/Components/GlassCard.vue';
import GlassButton from '@/Components/GlassButton.vue';
import GlassInput from '@/Components/GlassInput.vue';

const props = defineProps({
    flash: Object,
});

const route = inject('route');

const form = useForm({
    name: '',
    system_prompt: 'Anda adalah chatbot profesional yang dibentuk untuk membantu menjawab pertanyaan user berdasarkan informasi yang sudah diberikan.',
    knowledge_type: 'text',
    content: '',
    pdf_file: null,
});

const submit = () => {
    if (form.knowledge_type === 'text') {
        form.pdf_file = null;
    } else {
        form.content = '';
    }

    form.post(route('chatbots.store'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};

const knowledgeTypeChanged = () => {
    if (form.knowledge_type === 'text') {
        form.pdf_file = null;
    } else {
        form.content = '';
    }
};
</script>

<template>
    <Head title="Create Chatbot" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                Create New Chatbot
            </h2>
        </template>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash.error" class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 shadow-sm animate-pulse">
                {{ $page.props.flash.error }}
            </div>
            <div v-if="$page.props.flash.success" class="mb-4 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 shadow-sm">
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
                            rows="4"
                            class="w-full px-5 py-3 rounded-2xl border-0 bg-white/40 backdrop-blur-md shadow-inner text-gray-900 focus:ring-2 focus:ring-blue-500/50 focus:bg-white/60 transition-all duration-300 placeholder-gray-500/70"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">Define how the chatbot should behave and answer.</p>
                        <div v-if="form.errors.system_prompt" class="text-red-500 text-xs mt-1">{{ form.errors.system_prompt }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Knowledge Base Type</label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer group">
                                <input
                                    type="radio"
                                    v-model="form.knowledge_type"
                                    value="text"
                                    @change="knowledgeTypeChanged"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <span class="ml-2 text-gray-700 group-hover:text-indigo-600 transition-colors">Text Content</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input
                                    type="radio"
                                    v-model="form.knowledge_type"
                                    value="pdf"
                                    @change="knowledgeTypeChanged"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <span class="ml-2 text-gray-700 group-hover:text-indigo-600 transition-colors">PDF File</span>
                            </label>
                        </div>
                        <div v-if="form.errors.knowledge_type" class="text-red-500 text-xs mt-1">{{ form.errors.knowledge_type }}</div>
                    </div>

                    <div v-if="form.knowledge_type === 'text'">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <textarea
                            id="content"
                            v-model="form.content"
                            rows="8"
                            class="w-full px-5 py-3 rounded-2xl border-0 bg-white/40 backdrop-blur-md shadow-inner text-gray-900 focus:ring-2 focus:ring-blue-500/50 focus:bg-white/60 transition-all duration-300 placeholder-gray-500/70"
                            placeholder="Enter the knowledge base content here..."
                            required
                        ></textarea>
                        <div v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</div>
                    </div>

                    <div v-if="form.knowledge_type === 'pdf'">
                        <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-1">PDF File</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl bg-white/30 hover:bg-white/50 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="pdf_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="pdf_file" name="pdf_file" type="file" class="sr-only" @input="form.pdf_file = $event.target.files[0]" accept=".pdf" required />
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF up to 10MB</p>
                                <p v-if="form.pdf_file" class="text-sm font-semibold text-indigo-600 mt-2">Selected: {{ form.pdf_file.name }}</p>
                            </div>
                        </div>
                        <div v-if="form.errors.pdf_file" class="text-red-500 text-xs mt-1">{{ form.errors.pdf_file }}</div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200/50">
                        <Link :href="route('chatbots.index')" class="px-6 py-2.5 rounded-xl text-gray-700 bg-white/50 hover:bg-white/80 transition-colors font-medium">
                            Cancel
                        </Link>
                        <GlassButton
                            type="submit"
                            :disabled="form.processing"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        >
                            {{ form.processing ? 'Creating...' : 'Create Chatbot' }}
                        </GlassButton>
                    </div>
                </form>
            </GlassCard>
        </div>
    </AuthenticatedLayout>
</template>
