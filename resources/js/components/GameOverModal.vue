<template>
    <div class="fixed inset-0 bg-black/70 flex items-center justify-center p-6">
        <div class="bg-zinc-900 rounded-2xl p-6 w-full max-w-sm text-center">
            <h3 class="text-2xl font-bold mb-2">
                {{ winner === 'draw' ? 'Draw!' : `${winner === 'X' ? playerX : playerO} wins!` }}
            </h3>
            <div class="flex gap-3 mt-4 flex-col w-full max-w-sm">
                <button class="btn" @click="playAgain">
                    Play again
                </button>
                <RouterLink class="btn" to="/" @click="audio.playSound('click')">
                    Back
                </RouterLink>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted} from "vue";
import {useAudioStore} from "../stores/audioStore.js";
import {useVibration} from "../composables/useVibration.js";

const audio = useAudioStore();
const {vibrate} = useVibration();

defineProps({
    winner: String,
    playerX: String,
    playerO: String,
});

const emit = defineEmits(['again'])

onMounted(() => {
    audio.playSound('win');
    vibrate([100, 50, 200]);
});

const playAgain = () => {
    audio.playSound('playerSelect');
    emit('again')
}
</script>
