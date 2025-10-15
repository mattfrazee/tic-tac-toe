<template>
    <form class="p-6 space-y-3 text-center h-screen flex flex-col items-center justify-center" @submit.prevent="submit">
        <h2 class="text-4xl font-bold mb-4">Player Names</h2>
        <label class="relative block w-full">
            <input v-model="x" class="form-input w-full" placeholder="Player 1 Name (X's)"/>
            <span class="absolute w-20 left-2 top-2 text-sm p-2 rounded-lg bg-purple-200 font-bold text-purple-400">Player X</span>
        </label>
        <label class="relative block w-full">
            <input v-model="o" class="form-input" placeholder="Player 2 Name (O's)"/>
            <span class="absolute w-20 left-2 top-2 text-sm p-2 rounded-lg bg-purple-200 font-bold text-purple-400">Player O</span>
        </label>
        <div class="flex flex-col gap-4 w-full mt-10">
            <button class="btn-primary btn-glow w-full" @click="audio.playSound('click')">
                Start
            </button>
            <RouterLink class="btn-secondary w-2/3" to="/" @click="audio.playSound('click')">
                Back
            </RouterLink>
        </div>
    </form>
</template>
<script setup>
import {onMounted, ref} from 'vue'
import {useAudioStore} from "../stores/audioStore.js";

const audio = useAudioStore();

const x = ref(null)
const o = ref(null)
function submit() {
    emit('submit', {x: x.value, o: o.value})
}
const emit = defineEmits(['submit'])

onMounted(async () => {
    const { data } = await axios.get('/api/games?limit=1');
    if (data[0]) {
        x.value = data[0].player_x_name;
        o.value = data[0].player_o_name;
    }
})
</script>
<style scoped>
@reference "tailwindcss";

.form-input {
    /*@apply w-full px-4 py-3 rounded-xl bg-zinc-800 outline-none;*/
    @apply pl-24;
}
</style>
