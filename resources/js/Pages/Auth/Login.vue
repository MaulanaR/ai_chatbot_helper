<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import GlassButton from '@/Components/GlassButton.vue';
import GlassInput from '@/Components/GlassInput.vue';

const route = inject('route');

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
            <p class="text-sm text-gray-500 mt-2">Please sign in to your account</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Email</label>
                <GlassInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 ml-1">{{ form.errors.email }}</div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Password</label>
                <GlassInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                />
                <div v-if="form.errors.password" class="text-red-500 text-xs mt-1 ml-1">{{ form.errors.password }}</div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input name="remember" type="checkbox" v-model="form.remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-indigo-600 hover:text-indigo-900 font-medium"
                >
                    Forgot password?
                </Link>
            </div>

            <div class="pt-2">
                <GlassButton class="w-full justify-center text-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                    Sign in
                </GlassButton>
            </div>

            <div class="text-center mt-4">
                <span class="text-sm text-gray-600">Don't have an account? </span>
                <Link :href="route('register')" class="text-sm text-indigo-600 hover:text-indigo-900 font-bold transition-colors">
                    Sign up
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
