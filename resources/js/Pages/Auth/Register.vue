<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import GlassButton from '@/Components/GlassButton.vue';
import GlassInput from '@/Components/GlassInput.vue';

const route = inject('route');

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Create Account</h2>
            <p class="text-sm text-gray-500 mt-2">Join us to start building chatbots</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Name</label>
                <GlassInput
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your full name"
                />
                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1 ml-1">{{ form.errors.name }}</div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Email</label>
                <GlassInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 ml-1">{{ form.errors.email }}</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Password</label>
                    <GlassInput
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="Password"
                    />
                    <div v-if="form.errors.password" class="text-red-500 text-xs mt-1 ml-1">{{ form.errors.password }}</div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 ml-1 mb-1">Confirm</label>
                    <GlassInput
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm"
                    />
                    <div v-if="form.errors.password_confirmation" class="text-red-500 text-xs mt-1 ml-1">{{ form.errors.password_confirmation }}</div>
                </div>
            </div>

            <div class="pt-2">
                <GlassButton class="w-full justify-center text-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Sign Up
                </GlassButton>
            </div>

            <div class="text-center mt-4">
                <span class="text-sm text-gray-600">Already have an account? </span>
                <Link :href="route('login')" class="text-sm text-indigo-600 hover:text-indigo-900 font-bold transition-colors">
                    Sign in
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
