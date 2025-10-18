<template>
    <div class="flex justify-center items-center gap-1 mt-4 w-full">
        <div class="flex bg-zinc-800 rounded-full overflow-hidden shadow-lg border border-pink-500/20 w-full">
            <!-- Previous -->
            <button class="btn-audio" title="Previous Track" @click="$emit('prev')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M18 19V5l-8.5 7L18 19zM6 5v14h2V5H6z"/>
                </svg>
            </button>

            <!-- Play -->
            <button class="btn-audio" title="Play" @click="$emit('play')" v-if="isPaused || ! isPlaying">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>

            <!-- Pause -->
            <button class="btn-audio" title="Pause" @click="$emit('pause')" v-if="isPlaying">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                </svg>
            </button>

            <!-- Stop -->
            <button class="btn-audio" title="Stop" @click="$emit('stop')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M6 6h12v12H6z"/>
                </svg>
            </button>

            <!-- Next -->
            <button class="btn-audio" title="Next Track" @click="$emit('next')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M6 5v14l8.5-7L6 5zm10 0v14h2V5h-2z"/>
                </svg>
            </button>

            <!-- Loop -->
            <button :class="{'active': isLooping}" class="btn-audio relative" title="Toggle Loop" @click="$emit('loop')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M4 6a2 2 0 0 1 2-2h10V2l4 3-4 3V6H6a1 1 0 0 0-1 1v1H4V6z" />
                    <path d="M20 18a2 2 0 0 1-2 2H8v2l-4-3 4-3v2h10a1 1 0 0 0 1-1v-1h1v2z" />
                </svg>
                <span class="text-[9px] absolute font-bold">LOOP</span>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    isLooping: {
        type: Boolean,
        default: false
    },
    isPlaying: Boolean,
    isPaused: Boolean,
})
defineEmits(['play', 'pause', 'stop', 'next', 'prev', 'loop'])
</script>

<style scoped>
@reference "tailwindcss";

.btn-audio {
    @apply flex items-center justify-center w-12 h-12 flex-grow;
    @apply text-cyan-300 hover:text-pink-400 border-r border-zinc-700 hover:bg-zinc-700 last:border-r-0;
}

.btn-audio.active {
    @apply bg-pink-500 text-white;
}

.btn-audio:active .icon,
.btn-audio.active .icon {
    @apply scale-110;
}

.icon {
    @apply w-8 h-8 fill-current transition-all duration-200 ease-in-out origin-center;
}
</style>
