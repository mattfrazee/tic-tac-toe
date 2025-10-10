<template>
    <div class="h-full flex flex-col items-center justify-center gap-6 p-6 bg-[#ffe3c3]">
        <img src="/images/logo.png" alt="Tic Tac Toe" class="w-80 h-80 object-contain mb-4"/>
        <div class="flex flex-col gap-3 w-full max-w-sm">
            <RouterLink class="btn" to="/game" @click="playerSelect.play()">Play</RouterLink>
            <RouterLink class="btn" to="/scores" @click="playClickSound">Scores</RouterLink>
            <RouterLink class="btn" to="/settings" @click="playClickSound">Settings</RouterLink>
        </div>
    </div>
</template>
<script setup>
import {useSfx} from "../composables/useSfx.js";

const {playerSelect, gameOver, click} = useSfx();
function playClickSound() {
    click.play();
}
</script>
<style>
@reference "tailwindcss";

.btn {
    /*@apply py-3 rounded-xl bg-zinc-800 text-center font-semibold active:scale-95 shadow-lg shadow-fuchsia-600/20;*/
    @apply py-4 px-6 rounded-full font-semibold text-white shadow-lg active:scale-95 hover:scale-105 cursor-pointer text-center text-2xl;
    background: linear-gradient(135deg, #ff6ec4, #7873f5);
    box-shadow: 0 8px 15px rgba(255, 110, 196, 0.4), 0 4px 6px rgba(120, 115, 245, 0.3);
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.3s ease;
}

.btn:hover {
    background: linear-gradient(135deg, #ff85d1, #8a82f7);
    box-shadow: 0 12px 20px rgba(255, 110, 196, 0.6), 0 6px 10px rgba(120, 115, 245, 0.5);
}

.btn:active {
    box-shadow: 0 6px 10px rgba(255, 110, 196, 0.3), 0 3px 5px rgba(120, 115, 245, 0.2);
}
</style>
