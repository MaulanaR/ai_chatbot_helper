<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full px-5 py-3 rounded-2xl border-0 bg-white/40 backdrop-blur-md shadow-inner text-gray-900 focus:ring-2 focus:ring-blue-500/50 focus:bg-white/60 transition-all duration-300 placeholder-gray-500/70"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
