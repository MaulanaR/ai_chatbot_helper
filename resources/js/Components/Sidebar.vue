<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, inject } from 'vue';

const route = inject('route');
const page = usePage();

const props = defineProps({
    showingNavigationDropdown: Boolean
});

// Helper to check if route is active
const isRouteActive = (routePattern) => {
    const currentUrl = page.url;
    
    if (routePattern === 'dashboard') {
        return currentUrl === '/dashboard';
    }
    
    if (routePattern === 'chatbots.*') {
        return currentUrl.startsWith('/chatbots');
    }
    
    return false;
};
</script>

<template>
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white/80 backdrop-blur-xl border-r border-gray-200 transition-transform duration-300 ease-in-out transform lg:translate-x-0 shadow-lg lg:shadow-none"
        :class="{'translate-x-0': showingNavigationDropdown, '-translate-x-full': !showingNavigationDropdown}"
    >
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-gray-100">
             <Link :href="route('dashboard')" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold">
                    AI
                </div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 tracking-tight">
                    ChatBot SaaS
                </span>
            </Link>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-8 px-4 space-y-2">
            <Link :href="route('dashboard')" 
                class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                :class="isRouteActive('dashboard') 
                    ? 'bg-indigo-50 text-indigo-700 shadow-sm' 
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
            >
                <svg class="w-5 h-5 mr-3 transition-colors duration-200" 
                    :class="isRouteActive('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500'"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </Link>

            <Link :href="route('chatbots.index')" 
                class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                :class="isRouteActive('chatbots.*') 
                    ? 'bg-indigo-50 text-indigo-700 shadow-sm' 
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
            >
                <svg class="w-5 h-5 mr-3 transition-colors duration-200"
                    :class="isRouteActive('chatbots.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500'"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                My Chatbots
            </Link>

            <!-- Add more links here later -->
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="absolute bottom-0 w-full border-t border-gray-100 p-4 bg-gray-50/50 backdrop-blur-sm">
             <div class="flex items-center gap-3">
                <img class="h-9 w-9 rounded-full shadow-sm border border-white" 
                    :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=4f46e5&color=fff`" 
                    alt="User Avatar">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ $page.props.auth.user.name }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        {{ $page.props.auth.user.email }}
                    </p>
                </div>
             </div>
             <div class="mt-4 flex gap-2">
                <Link :href="route('profile.edit')" class="flex-1 py-1.5 px-2 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 text-center shadow-sm transition-colors">
                    Profile
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="flex-1 py-1.5 px-2 text-xs font-medium text-red-600 bg-white border border-red-100 rounded-lg hover:bg-red-50 text-center shadow-sm transition-colors">
                    Logout
                </Link>
             </div>
        </div>
    </aside>
</template>
