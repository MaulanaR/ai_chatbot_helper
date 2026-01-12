<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, inject } from 'vue';

const route = inject('route');

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    widgetBubbleUrl: {
        type: String,
        required: true,
    },
});

const features = [
    {
        title: 'Smart AI Chatbots',
        description: 'Deploy intelligent agents that understand your business context and answer customer queries 24/7.',
        icon: 'BotIcon'
    },
    {
        title: 'Instant Deployment',
        description: 'Get up and running in minutes. Simply upload your data, customize your bot, and embed it on your site.',
        icon: 'RocketIcon'
    },
    {
        title: 'Knowledge Base',
        description: 'Train your chatbots with your own documents (PDF, Text) to provide accurate and relevant responses.',
        icon: 'BookIcon'
    }
];
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 text-black/50 overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        <!-- Decoration -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 left-0 -mt-20 -ml-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 -mb-20 -ml-20 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/60 backdrop-blur-lg border-b border-white/40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center">
                        <Link href="/" class="flex flex-col">
                            <span class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 tracking-tighter">
                                ChatBot SaaS
                            </span>
                        </Link>
                    </div>

                    <div v-if="canLogin" class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')" class="font-semibold text-gray-600 hover:text-indigo-600 transition">
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="font-medium text-gray-600 hover:text-indigo-600 transition">
                                Log in
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="px-5 py-2.5 rounded-xl bg-gray-900 text-white font-medium hover:bg-gray-800 transition shadow-lg shadow-gray-900/20 transform hover:-translate-y-0.5">
                                Start Free
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <!-- Hero Section -->
            <section class="relative pt-20 pb-32 lg:pt-32">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-sm font-semibold mb-8 animate-fade-in-up">
                        <span class="flex h-2 w-2 relative mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        New: GPT-4o Integration Live
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 mb-8 animate-fade-in-up animation-delay-100">
                        Build Custom AI Chatbots <br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">
                            trained on your data.
                        </span>
                    </h1>
                    
                    <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto mb-10 animate-fade-in-up animation-delay-200 leading-relaxed">
                        Instantly resolve 80% of support queries. Upload your documents, customize your bot, and embed it anywhere. No coding required.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up animation-delay-300">
                        <template v-if="$page.props.auth.user">
                             <Link :href="route('dashboard')" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-lg hover:shadow-xl hover:shadow-indigo-500/30 transition transform hover:-translate-y-1">
                                Go to Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('register')" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-lg hover:shadow-xl hover:shadow-indigo-500/30 transition transform hover:-translate-y-1">
                                Get Started for Free
                            </Link>
                            <a href="#features" class="px-8 py-4 rounded-2xl bg-white text-gray-700 font-bold text-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition">
                                Learn More
                            </a>
                        </template>
                    </div>

                    <!-- Hero Image / Preview -->
                    <div class="mt-20 relative mx-auto max-w-5xl">
                        <div class="rounded-3xl bg-gray-900/5 p-2 ring-1 ring-inset ring-gray-900/10 lg:-m-4 lg:rounded-4xl lg:p-4">
                            <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-200">
                                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                </div>
                                <div class="px-8 py-12 bg-white flex flex-col items-center justify-center min-h-[400px] text-gray-500">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                    </div>
                                    <p class="text-xl font-medium">Chatbot Preview Area</p>
                                    <p class="text-sm mt-2">Interact with your trained assistant here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-24 bg-white/50 backdrop-blur-sm relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
                        <h3 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Everything you need to automate support
                        </h3>
                    </div>

                    <div class="grid md:grid-cols-3 gap-12">
                        <div v-for="feature in features" :key="feature.title" class="group relative bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/30">
                                    <svg v-if="feature.icon === 'BotIcon'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <svg v-else-if="feature.icon === 'RocketIcon'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <svg v-else class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 11.754 5 10 5s-3.168.477-4.5 1.253m0 13C6.832 18.477 5.754 18 7.5 18c-1.746 0-3.332.477-4.5 1.253m0-13V8.25m9 0v-2.247C15.168 5.477 13.754 5 12 5s-3.168.477-4.5 1.253m0 13V8.25"></path></svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900 mb-3">{{ feature.title }}</h4>
                                <p class="text-gray-600 leading-relaxed">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing Section -->
            <section id="pricing" class="py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="text-center mb-16">
                        <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Pricing</h2>
                        <h3 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Plans for every stage
                        </h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                        <!-- Free Plan -->
                        <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 flex flex-col relative overflow-hidden">
                             <div class="mb-6">
                                <h3 class="text-2xl font-bold text-gray-900">Starter</h3>
                                <p class="text-gray-500 mt-2">Perfect for trying out the platform.</p>
                            </div>
                            <div class="mb-8">
                                <span class="text-5xl font-extrabold text-gray-900">$0</span>
                                <span class="text-gray-500 font-medium">/month</span>
                            </div>
                            <ul class="space-y-4 mb-8 flex-1">
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    1 Active Chatbot
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Limit 20 chats / minute
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Basic Analytics
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Community Support
                                </li>
                            </ul>
                            <Link :href="route('register')" class="w-full py-3 px-6 rounded-xl bg-gray-100 text-gray-800 font-bold hover:bg-gray-200 transition text-center">
                                Start for Free
                            </Link>
                        </div>

                        <!-- Pro Plan -->
                        <div class="bg-white rounded-3xl p-8 shadow-xl border-2 border-indigo-500 flex flex-col relative overflow-hidden transform md:-translate-y-4">
                            <div class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">
                                Recommended
                            </div>
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-gray-900">Pro</h3>
                                <p class="text-gray-500 mt-2">For growing businesses.</p>
                            </div>
                            <div class="mb-8">
                                <span class="text-5xl font-extrabold text-gray-900">$29</span>
                                <span class="text-gray-500 font-medium">/month</span>
                            </div>
                            <ul class="space-y-4 mb-8 flex-1">
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <strong>5 Active Chatbots</strong>
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <strong>Unlimited Chats</strong>
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Advanced Analytics
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Priority Support
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Custom Domain (Soon)
                                </li>
                            </ul>
                            <Link :href="route('register')" class="w-full py-3 px-6 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 transition text-center">
                                Get Started
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="bg-gray-50 border-t border-gray-200">
                <iframe
                    :src="widgetBubbleUrl"
                    class="fixed bottom-6 right-6 z-50 border-0 shadow-none"
                    style="width: 400px; height: 600px; max-height: 85vh; max-width: 90vw;"
                    allowtransparency="true"
                ></iframe>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="flex justify-center space-x-6 md:order-2">
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Facebook</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Twitter</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">GitHub</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                            </a>
                        </div>
                        <div class="mt-8 md:mt-0 md:order-1">
                            <p class="text-center text-base text-gray-400">
                                &copy; 2024 ChatBot SaaS. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>
</template>

<style>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 20px, 0);
    }
    to {
        opacity: 1;
        transform: none;
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
}

.animation-delay-100 {
    animation-delay: 0.1s;
}
.animation-delay-200 {
    animation-delay: 0.2s;
}
.animation-delay-300 {
    animation-delay: 0.3s;
}
</style>
