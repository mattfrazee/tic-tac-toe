<template>
    <Transition
        enter-active-class="transition ease-out duration-150"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center p-6">
            <div class="bg-zinc-900 rounded-xl shadow-lg p-6 w-full sm:max-w-sm text-center">
                <h3 class="text-2xl font-bold mb-2">
                    {{ winner === 'draw' ? 'Draw!' : `${winner === 'X' ? playerX : playerO} wins!` }}
                </h3>
                <div class="flex gap-4 mt-4 flex-col w-full -max-w-sm">
                    <SoundFxEvent file="playerSelect">
                        <button class="btn-primary btn-glow w-5/6 mx-auto" @click="$emit('again')">
                            Play Again
                        </button>
                    </SoundFxEvent>
                    <SoundFxEvent file="click">
                        <RouterLink class="btn-secondary btn-small mx-auto" to="/">
                            Main Menu
                        </RouterLink>
                    </SoundFxEvent>
                </div>
                <SoundFxEvent file="win3" autoplay />
            </div>
        </div>
    </Transition>
</template>
<script setup>
import {onMounted} from "vue";
import {useVibration} from "../composables/useVibration.js";
import SoundFxEvent from "./SoundFxEvent.vue";

const {vibrate} = useVibration()

defineProps({
    winner: String,
    playerX: String,
    playerO: String,
})

defineEmits(['again'])

onMounted(() => {
    vibrate([100, 50, 200])
})
</script>
