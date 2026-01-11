<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import GlassCard from '@/Components/GlassCard.vue';
import GlassButton from '@/Components/GlassButton.vue';
import GlassInput from '@/Components/GlassInput.vue';

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const updateProfile = () => {
    form.patch(route('profile.update'));
};
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <GlassCard class="p-8">
                    <section class="max-w-xl">
                        <header>
                            <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Update your account's profile information and email address.
                            </p>
                        </header>

                        <form @submit.prevent="updateProfile" class="mt-8 space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <GlassInput
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <GlassInput
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                />
                                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                            </div>

                            <div class="flex items-center gap-4">
                                <GlassButton
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    Save
                                </GlassButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm text-green-600 font-medium">Saved.</p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </GlassCard>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
