<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject, ref } from 'vue';
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
    deleted_knowledge_ids: [],
    new_knowledge_type: '',
    new_knowledge_content: '',
    new_knowledge_pdf: null,
    // Use _method spoofing for PATCH requests with files in Laravel/Inertia
    _method: 'PATCH'
});

const localKnowledgeBases = ref([...props.chatbot.knowledge_bases]);

const markForDeletion = (id) => {
    if (confirm('Are you sure you want to delete this knowledge source?')) {
        form.deleted_knowledge_ids.push(id);
        localKnowledgeBases.value = localKnowledgeBases.value.filter(kb => kb.id !== id);
    }
};

const submit = () => {
    // We must use POST with _method spoofing for file uploads in Laravel/Inertia
    form.post(route('chatbots.update', props.chatbot.id), {
        preserveScroll: true,
        onError: (errors) => {
            console.error('Update failed', errors);
        }
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

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12 text-gray-900">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.error" class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 shadow-sm animate-pulse">
                {{ $page.props.flash.error }}
            </div>
            <div v-if="$page.props.flash?.success" class="mb-4 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 shadow-sm">
                {{ $page.props.flash.success }}
            </div>

            <GlassCard>
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- General Settings -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Chatbot Name</label>
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
                            <label for="system_prompt" class="block text-sm font-semibold text-gray-700 mb-1">System Prompt</label>
                            <textarea
                                id="system_prompt"
                                v-model="form.system_prompt"
                                rows="6"
                                class="w-full px-5 py-3 rounded-2xl border-0 bg-white shadow-inner text-gray-900 ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500/50 focus:bg-white transition-all duration-300 placeholder-gray-400"
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-2">Define how the chatbot should behave and answer.</p>
                            <div v-if="form.errors.system_prompt" class="text-red-500 text-xs mt-1">{{ form.errors.system_prompt }}</div>
                        </div>
                    </div>

                    <!-- Current Knowledge Base -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">Existing Knowledge Base</label>
                        <div v-if="localKnowledgeBases.length > 0" class="space-y-3">
                            <div v-for="kb in localKnowledgeBases" :key="kb.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 group">
                                <div class="flex items-center gap-4">
                                    <div :class="kb.type === 'pdf' ? 'bg-red-100 text-red-600' : 'bg-indigo-100 text-indigo-600'" class="h-10 w-10 rounded-xl flex items-center justify-center">
                                        <svg v-if="kb.type === 'pdf'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-gray-900 truncate max-w-[250px]">
                                            {{ kb.type === 'pdf' ? (kb.file_path ? kb.file_path.split('/').pop() : 'PDF Document') : 'Text Document' }}
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <p class="text-xs text-gray-500">{{ Math.round(kb.content?.length / 1024 * 10) / 10 }} KB content</p>
                                            <a v-if="kb.type === 'pdf' && kb.file_path" 
                                               :href="`/storage/${kb.file_path}`" 
                                               target="_blank"
                                               class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1"
                                            >
                                                View PDF
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="markForDeletion(kb.id)"
                                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                                    title="Delete source"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                             <p class="text-sm text-gray-400">No knowledge base sources available. Add one below.</p>
                        </div>
                    </div>

                    <!-- Add New Knowledge -->
                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-sm font-semibold text-gray-700 mb-4">Add New Knowledge</label>
                        <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100/50 space-y-4">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="form.new_knowledge_type" value="text" class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Plain Text</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="form.new_knowledge_type" value="pdf" class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">PDF File</span>
                                </label>
                                <button v-if="form.new_knowledge_type" type="button" @click="form.new_knowledge_type = ''; form.new_knowledge_content = ''; form.new_knowledge_pdf = null;" class="text-xs text-gray-400 hover:text-gray-600 font-medium ml-auto">Clear selection</button>
                            </div>

                            <div v-if="form.new_knowledge_type === 'text'" class="animate-in fade-in slide-in-from-top-2">
                                <textarea
                                    v-model="form.new_knowledge_content"
                                    placeholder="Paste your knowledge base text here..."
                                    rows="4"
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500/50 text-sm"
                                ></textarea>
                                <div v-if="form.errors.new_knowledge_content" class="text-red-500 text-xs mt-1">{{ form.errors.new_knowledge_content }}</div>
                            </div>

                            <div v-if="form.new_knowledge_type === 'pdf'" class="animate-in fade-in slide-in-from-top-2">
                                <div class="flex items-center justify-center w-full">
                                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-400">PDF (MAX. 10MB)</p>
                                        </div>
                                        <input 
                                            type="file" 
                                            class="hidden" 
                                            accept="application/pdf"
                                            @input="form.new_knowledge_pdf = $event.target.files[0]"
                                        />
                                    </label>
                                </div>
                                <div v-if="form.new_knowledge_pdf" class="mt-2 flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 text-xs text-gray-600">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    {{ form.new_knowledge_pdf.name }}
                                </div>
                                <div v-if="form.errors.new_knowledge_pdf" class="text-red-500 text-xs mt-1">{{ form.errors.new_knowledge_pdf }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-8 border-t border-gray-100">
                        <Link :href="route('chatbots.show', chatbot.id)" class="px-6 py-2.5 rounded-xl text-gray-600 bg-gray-50 hover:bg-gray-100 transition-colors font-semibold text-sm">
                            Cancel
                        </Link>
                        <GlassButton
                            type="submit"
                            :disabled="form.processing"
                            class="min-w-[140px]"
                        >
                            <span v-if="form.processing" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Updating...
                            </span>
                            <span v-else>Save Changes</span>
                        </GlassButton>
                    </div>
                </form>
            </GlassCard>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.animate-in {
    animation-duration: 0.3s;
}
</style>

