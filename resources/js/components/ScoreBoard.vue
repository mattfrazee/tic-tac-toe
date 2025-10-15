<template>
    <div class="flex flex-col justify-between px-4 py-6">
        <div v-if="playerO" :class="{'bg-green-800': turn === 'O'}"
             class="flex flex-col text-center transform rotate-180 py-2">
            <span class="text-sm opacity-70">O</span>
            <span class="text-3xl font-bold text-cyan-400 line-clamp-1">{{ playerO }}</span>
            <div class="text-2xl font-black text-center">{{ score.O }} : {{ score.X }}</div>
        </div>

        <div v-if="playerX" :class="{'bg-green-800': turn === 'X'}"
             class="flex flex-col text-center py-2">
            <span class="text-sm opacity-70">X</span>
            <span class="text-3xl font-bold text-fuchsia-400 line-clamp-1">{{ playerX }}</span>
            <div class="text-2xl font-black text-center">{{ score.X }} : {{ score.O }}</div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    playerX: String,
    playerO: String,
    score: Object,
    turn: String,
});
</script>
