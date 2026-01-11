<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, inject } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';

const route = inject('route');
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="title" />

        <!-- Sidebar -->
        <Sidebar :showingNavigationDropdown="showingNavigationDropdown" />

        <!-- Mobile overlay -->
        <div 
            v-show="showingNavigationDropdown" 
            @click="showingNavigationDropdown = false"
            class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden transition-opacity"
        ></div>

        <!-- Main Content Area -->
        <div class="lg:pl-64 flex flex-col min-h-screen transition-all duration-300">
            <!-- Top Bar -->
            <header class="sticky top-0 z-30 flex items-center justify-between h-20 px-6 py-4 bg-white/80 backdrop-blur-xl border-b border-gray-200 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="p-2 -ml-2 text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <div v-if="$slots.header">
                        <slot name="header" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Right side of top bar (notifications, etc. - placeholder for now) -->
                    <div class="hidden sm:flex items-center gap-2">
                         <span class="text-sm text-gray-500">Welcome, {{ $page.props.auth.user.name }}</span>
                         <img class="h-8 w-8 rounded-full shadow-sm border border-white" :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=4f46e5&color=fff`" alt="User Avatar">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 py-8 px-6">
                 <slot />
            </main>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
    }
}
</script>
