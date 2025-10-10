<template>
    <div class="fixed inset-0 bg-black/70 flex items-center justify-center p-6">
        <div class="bg-zinc-900 rounded-2xl p-6 w-full max-w-sm text-center">
            <h3 class="text-2xl font-bold mb-2">
<!--                {{ winner === 'draw' ? 'Draw!' : `${winner} wins!` }}-->
                {{ winner === 'draw' ? 'Draw!' : `${winner === 'X' ? playerX : playerO} wins!` }}
            </h3>
            <div class="flex gap-3 mt-4">
                <button class="flex-1 btn" @click="$emit('again')">Play again</button>
                <RouterLink class="flex-1 btn" to="/">Home</RouterLink>
            </div>
        </div>
    </div>
</template>
<script setup>
defineProps({
    winner: String,
    playerX: String,
    playerO: String
});
</script>
<style>
@reference "tailwindcss";

.btn {
    @apply py-3 rounded-xl bg-fuchsia-600 text-white font-bold active:scale-95;
}
</style>
