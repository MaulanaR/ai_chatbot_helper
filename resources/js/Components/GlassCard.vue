<template>
    <div class="glass-panel rounded-3xl p-6 transition-all duration-300 hover:shadow-2xl">
        <slot />
    </div>
</template>

<style scoped>
.glass-panel {
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
}
</style>
