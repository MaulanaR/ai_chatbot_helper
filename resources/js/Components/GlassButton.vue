<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary', // primary, secondary, danger
    }
});
</script>

<template>
    <button
        :type="type"
        :class="[
            'inline-flex items-center px-6 py-3 border border-transparent rounded-2xl font-semibold text-sm capitalize tracking-wide transition-all duration-300 ease-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl',
            variant === 'primary' ? 'bg-black/80 hover:bg-black text-white focus:ring-gray-500' : '',
            variant === 'secondary' ? 'bg-white/50 hover:bg-white/80 text-gray-800 border-white/40 focus:ring-gray-300' : '',
            variant === 'danger' ? 'bg-red-500/80 hover:bg-red-600 text-white focus:ring-red-500' : ''
        ]"
    >
        <slot />
    </button>
</template>
