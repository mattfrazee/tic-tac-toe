<template>
    <form class="p-6 space-y-3" @submit.prevent="submit">
        <input v-model="x" class="input" placeholder="Player X name"/>
        <input v-model="o" class="input" placeholder="Player O name"/>
        <button class="btn w-full">Start</button>
    </form>
</template>
<script setup>
import {ref} from 'vue'

const emit = defineEmits(['submit'])
const x = ref('Player X')
const o = ref('Player O')

function submit() {
    emit('submit', {x: x.value, o: o.value})
}
</script>
<style scoped>
@reference "tailwindcss";

.input {
    @apply w-full px-4 py-3 rounded-xl bg-zinc-800 outline-none;
}

.btn {
    @apply py-3 rounded-xl bg-fuchsia-600 text-white font-bold active:scale-95;
}
</style>
