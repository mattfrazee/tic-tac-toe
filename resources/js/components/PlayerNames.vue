<template>
    <form class="p-6 space-y-3 text-center h-screen flex flex-col items-center justify-center" @submit.prevent="submit">
        <h2 class="text-4xl font-bold mb-4">Player Names</h2>
<!--        <label class="relative block">-->
            <input v-model="x" class="input" placeholder="Player 1 Name (X's)"/>
<!--            <span class="text-sm p-2 rounded-lg bg-gray-500 text-black">Player 1</span>-->
<!--        </label>-->
        <input v-model="o" class="input" placeholder="Player 2 Name (O's)"/>
        <div class="flex flex-col gap-3 w-full max-w-sm">
            <button class="btn" @click="audio.playSound('click')">
                Start
            </button>
            <RouterLink class="btn" to="/" @click="audio.playSound('click')">
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
<style>
@reference "tailwindcss";

.input {
    @apply w-full px-4 py-3 rounded-xl bg-zinc-800 outline-none;
}
</style>
