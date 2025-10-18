<template>
    <div :style="{ height, width }" aria-label="Audio levels" class="flex items-end gap-1" role="img">
        <div v-for="i in bars" :key="i"
             :class="[playing ? 'animate-eq' : 'animate-stop', colorClass]"
             :style="{
                animationDelay: playing ? `${(i * 97) % 420}ms` : '0ms',
                animationDuration: playing ? `${baseMs + (i * 73) % 400}ms` : '0ms'
             }"
             class="flex-1 h-full bg-cyan-300/90 dark:bg-cyan-300 transition-all delay-100 ease-in-out duration-300 origin-bottom"></div>
    </div>
</template>

<script setup>
const props = defineProps({
    playing: {type: Boolean, default: false},
    bars: {type: Number, default: 2},
    baseMs: {type: Number, default: 900},
    height: {type: String, default: '24px'},
    width: {type: String, default: '28px'},
    colorClass: {type: String, default: 'bg-cyan-300'},
})
</script>

<style scoped>
@reference "tailwindcss";

@keyframes eq-bounce {
    0% {
        transform: scaleY(0.25)
    }
    25% {
        transform: scaleY(0.9)
    }
    50% {
        transform: scaleY(0.4)
    }
    75% {
        transform: scaleY(0.75)
    }
    100% {
        transform: scaleY(0.3)
    }
}

.animate-eq {
    transform-origin: bottom center;
    animation-name: eq-bounce;
    animation-timing-function: cubic-bezier(.25, .8, .25, 1);
    animation-iteration-count: infinite;
}

.animate-stop {
    @apply animate-none scale-y-0;
}

@media (prefers-reduced-motion: reduce) {
    .animate-eq {
        animation: none !important;
    }
}
</style>
