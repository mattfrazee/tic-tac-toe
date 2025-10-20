<template>
    <div class="h-full text-center safe-area-container">
        <div class="h-full p-6">
            <h2 class="text-4xl font-bold mb-4">Scoreboard</h2>

            <div v-if="loading" class="flex flex-col items-center justify-center h-full space-y-3 text-gray-300">
                <div class="flex space-x-2">
                    <div class="w-3 h-3 bg-cyan-400 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                    <div class="w-3 h-3 bg-cyan-400 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                    <div class="w-3 h-3 bg-cyan-400 rounded-full animate-bounce"></div>
                </div>
                <span class="text-xl text-cyan-300 font-medium">Loading scores...</span>
            </div>

            <div class="pb-32" v-else>
                <div v-if="scores.length === 0" class="text-gray-500">
                    No games recorded yet.
                </div>

                <div v-else class="space-y-4">
                    <div v-for="(game, index) in scores" :key="game.id"
                        class="p-4 bg-gray-800 rounded-xl shadow-md cursor-pointer hover:bg-gray-700 transition"
                        @click="selectGame(game)">
                        <div class="flex justify-between gap-x-4- items-center text-gray-200">
                            <div class="text-xl text-fuchsia-500 font-black uppercase flex-none mr-4">{{ (game.winner).substring(0,1) }}</div>
                            <div class="text-left flex-1">
                                <div class="font-semibold line-clamp-1">{{ game.player_x_name }} vs. {{ game.player_o_name }}</div>
                                <div class="text-sm text-gray-400">
                                    Winner:
                                    <span v-if="game.winner === 'X'">
                                        {{ game.player_x_name }}
                                    </span>
                                    <span v-else-if="game.winner === 'O'">
                                        {{ game.player_o_name }}
                                    </span>
                                    <span v-else class="italic text-gray-500">
                                        Draw
                                    </span>
                                </div>
                            </div>
                            <div class="text-sm text-gray-400">Moves: {{ game.moves_count }}</div>
                        </div>
                    </div>
                </div>

                <div v-if="selectedGame" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                    <div class="bg-gray-900 rounded-xl p-6 w-80 text-center relative">
                        <button class="absolute top-2 right-3 text-gray-400 hover:text-white" @click="selectedGame = null">✕</button>
                        <div class="text-xl font-bold mb-3 text-cyan-300 line-clamp-1">
                            {{ selectedGame.player_x_name }} vs. {{ selectedGame.player_o_name }}
                        </div>
                        <div class="grid grid-cols-3 gap-2 w-64 mx-auto">
                            <div v-for="(cell, i) in previewBoard" :key="i"
                                 class="w-20 h-20 flex items-center justify-center text-4xl font-extrabold rounded bg-gray-800">
                                {{ cell }}
                            </div>
                        </div>
                        <div class="mt-3 font-bold text-lg">
                            <span v-if="selectedGame.winner === 'X'">{{ selectedGame.player_x_name }} was the winner!</span>
                            <span v-else-if="selectedGame.winner === 'O'">{{ selectedGame.player_o_name }} was the winner!</span>
                            <span v-else>Draw Game</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 w-full fixed bottom-0 left-0 max-w-sm">
                    <div class="w-screen px-6">
                        <SoundFxEvent file="click">
                            <RouterLink class="mb-10 w-full sm:w-1/2 btn-primary" to="/">
                                Back
                            </RouterLink>
                        </SoundFxEvent>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, onUnmounted, ref} from 'vue'
import {useAudioStore} from "../stores/audioStore.js";
import SoundFxEvent from "../components/SoundFxEvent.vue";

const audio = useAudioStore();
const scores = ref([])
const loading = ref(true);
const selectedGame = ref(null);
const previewBoard = ref(Array(9).fill(''));

async function selectGame(game) {
    audio.playSound('click');
    try {
        const response = await axios.get(`/api/games/${game.id}`);
        selectedGame.value = response.data;
        // build board
        const board = Array(9).fill('');
        for (const move of response.data.moves) {
            const index = move.row * 3 + move.col;
            board[index] = move.mark;
        }
        previewBoard.value = board;
    } catch (err) {
        console.error('Failed to fetch game details:', err);
    }
}

onMounted(async () => {
    document.querySelector('html').classList.add('safe-area');
    try {
        const response = await axios.get('/api/games')
        scores.value = response.data
    } catch (error) {
        console.error('Failed to fetch scores:', error)
    } finally {
        setTimeout(() => loading.value = false, 1000)
    }
})
onUnmounted(() => document.querySelector('html').classList.remove('safe-area'))
</script>
