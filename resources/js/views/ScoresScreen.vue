<template>
    <div class="h-full p-6 text-center">
        <h2 class="text-2xl font-bold mb-4">Scoreboard</h2>

        <div v-if="loading" class="flex flex-col items-center justify-center h-full space-y-3 text-gray-300">
            <div class="flex space-x-2">
                <div class="w-3 h-3 bg-cyan-400 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                <div class="w-3 h-3 bg-cyan-400 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                <div class="w-3 h-3 bg-cyan-400 rounded-full animate-bounce"></div>
            </div>
            <span class="text-xl text-cyan-300 font-medium">Loading scores...</span>
        </div>

        <div v-else>
            <div v-if="scores.length === 0" class="text-gray-500">
                No games recorded yet.
            </div>

            <div v-else class="space-y-4">
                <div v-for="(game, index) in scores" :key="game.id" class="p-4 bg-gray-800 rounded-xl shadow-md">
                    <div class="flex justify-between items-center text-gray-200">
                        <div>
                            <div class="font-semibold">{{ game.player_x_name }} vs {{ game.player_o_name }}</div>
                            <div class="text-sm text-gray-400">
                                Winner:
                                <span v-if="game.winner">{{ game.winner }}</span>
                                <span v-else class="italic text-gray-500">Draw</span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400">Moves: {{ game.moves_count }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'

const scores = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await axios.get('/api/games')
        scores.value = response.data
    } catch (error) {
        console.error('Failed to fetch scores:', error)
    } finally {
        setTimeout(() => loading.value = false, 1000)
    }
})
</script>
