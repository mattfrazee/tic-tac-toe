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

            <div class="pb-32" :class="{'h-full flex items-center justify-center': scores.length === 0}" v-else>
                <div v-if="scores.length === 0" class="text-gray-400 text-lg font-bold">
                    <div class="text-6xl mb-4">
                        😞
                    </div>
                    <p class="mb-8">
                        No games recorded yet.
                    </p>
                    <SoundFxEvent file="click">
                        <RouterLink class="btn-small btn-primary" to="/game">
                            Play a Match
                        </RouterLink>
                    </SoundFxEvent>
                </div>

                <div v-else class="space-y-4">

                    <div class="settings-title text-left mb-4">
                        Top Players
                    </div>
                    <div class="grid grid-cols-1 gap-4 items-center justify-center pb-15">

                        <div v-for="(leader, id) in leaders" :key="leader.name" :class="{
                            'bg-purple-800': id === 0,
                            'bg-purple-950': id > 0
                        }" @click="selectedLeader = leader" class="bg-purple-800- rounded-xl shadow-md cursor-pointer hover:bg-purple-700 transition">
                            <SoundFxEvent file="click3">
                                <div class="p-4 flex gap-x-4 items-center text-gray-200 relative">
                                    <div class="text-3xl text-fuchsia-500 font-black uppercase flex-none mr-4">
                                        #{{ id + 1 }}
                                    </div>
                                    <div class="text-left flex-1">
    <!--                                    <div v-if="id === 0" class="text-2xl absolute -left-2 -top-4 -rotate-12">👑</div>-->
                                        <div class="font-semibold text-2xl line-clamp-1">
                                            {{ leader.name }}
                                        </div>
                                        <div class="text-sm text-white">
                                            {{ leader.name }} wins {{ leader.win_percentage }}% of the time.
                                        </div>
                                    </div>
                                    <div class="text-gray-300 text-right text-sm">
                                        Won: <span class="min-w-5 inline-block font-bold">{{ leader.games_won }}</span>
                                        <br>Lost: <span class="min-w-5 inline-block font-bold">{{ leader.games_lost }}</span>
                                        <br>Drawn: <span class="min-w-5 inline-block font-bold">{{ leader.games_drawn }}</span>
                                    </div>
                                </div>
                            </SoundFxEvent>
                        </div>
                    </div>

                    <Modal :visible="!!selectedLeader" @close="selectedLeader = null">
                        <template #title>
                            <div class="text-3xl font-bold mb-3 text-cyan-300 line-clamp-1">
                                {{ selectedLeader.name }}
                            </div>
                        </template>
                        <template #message>
                            <div class="relative flex items-center justify-center w-full h-32 my-4">
                                <svg viewBox="0 0 36 36" class="size-full">
                                    <path class="text-zinc-700 stroke-current fill-none" stroke-width="3.8"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path :class="{
                                        'text-cyan-400': selectedLeader.win_percentage !== 100,
                                        'text-green-400': selectedLeader.win_percentage === 100
                                    }" class="text-cyan-400 transition-all duration-300 fill-none stroke-current" stroke-linecap="round" stroke-width="3.8"
                                          :stroke-dasharray="`${selectedLeader.win_percentage} ${100 - selectedLeader.win_percentage}`"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    ></path>
                                </svg>

                                <div :class="{
                                        'text-cyan-300': selectedLeader.win_percentage !== 100,
                                        'text-green-300': selectedLeader.win_percentage === 100
                                    }"
                                     class="absolute font-bold text-xl">
                                    {{ selectedLeader.win_percentage }}%
                                </div>
                            </div>

                            <div class="flex justify-center gap-8 w-full mt-4">
                                <!-- Played -->
                                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-zinc-900 border-4 border-zinc-700 shadow-lg">
                                    <div class="text-center">
                                        <p class="text-[11px] text-gray-300 font-semibold ">Played</p>
                                        <p class="text-xl font-extrabold leading-6 text-white">{{ selectedLeader.games_played }}</p>
                                    </div>
                                </div>

                                <!-- Won -->
                                <div class="flex items-center justify-center w-20 h-20 rounded-full border-4 border-green-400 bg-green-900/50 shadow-[0_0_15px_rgba(74,222,128,0.7)] animate-pulse-slow">
                                    <div class="text-center">
                                        <p class="text-[11px] text-green-300 font-semibold ">Won</p>
                                        <p class="text-xl font-extrabold leading-6 text-green-200">{{ selectedLeader.games_won }}</p>
                                    </div>
                                </div>

                                <!-- Lost -->
                                <div class="flex items-center justify-center w-20 h-20 rounded-full border-4 border-red-400 bg-red-900/50 shadow-[0_0_12px_rgba(248,113,113,0.6)]">
                                    <div class="text-center">
                                        <p class="text-[11px] text-red-300 font-semibold ">Lost</p>
                                        <p class="text-xl font-extrabold leading-6 text-red-200">{{ selectedLeader.games_lost }}</p>
                                    </div>
                                </div>
                            </div>

                            <p class="italic text-gray-300 text-lg text-center my-4">
                                {{ funMessage }}
                            </p>
                        </template>
                    </Modal>

                    <div class="settings-title text-left mb-4">
                        Past Games
                    </div>
                    <div v-for="(game, index) in scores" :key="game.id"
                        class="p-4 bg-gray-800 rounded-xl shadow-md cursor-pointer hover:bg-gray-700 transition"
                        @click="selectGame(game)">
                        <div class="flex justify-between gap-x-4- items-center text-gray-200">
                            <div class="text-xl text-fuchsia-500 font-black uppercase flex-none mr-4">{{ (game.winner).substring(0,1) }}</div>
                            <div class="text-left flex-1">
                                <div class="font-semibold line-clamp-1">{{ game.player_x_name }} vs. {{ game.player_o_name }}</div>
                                <div class="text-sm text-gray-400">
                                    Winner:
                                    <span v-if="game.winner === PlayerMark.X">
                                        {{ game.player_x_name }}
                                    </span>
                                    <span v-else-if="game.winner === PlayerMark.O">
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

                <Modal :visible="!!selectedGame" @close="selectedGame = null">
                    <template #title>
                        <div class="text-xl font-bold mb-3 text-cyan-300 line-clamp-1">
                            {{ selectedGame.player_x_name }} vs. {{ selectedGame.player_o_name }}
                        </div>
                    </template>
                    <template #message>
                        <Board :size="selectedGame.board_size"
                               :cells="buildCells(selectedGame)"
                               :turn="null"
                               :winningLine="getWinningLine(selectedGame)"/>
                        <div class="mt-3 font-bold text-lg">
                            <span v-if="selectedGame.winner === PlayerMark.X">{{ selectedGame.player_x_name }} was the winner!</span>
                            <span v-else-if="selectedGame.winner === PlayerMark.O">{{ selectedGame.player_o_name }} was the winner!</span>
                            <span v-else>Draw Game</span>
                        </div>
                    </template>
                </Modal>

                <div class="flex flex-col gap-3 w-full fixed bottom-0 left-1/2 -translate-x-1/2 max-w-sm">
                    <SoundFxEvent file="click">
                        <RouterLink class="mb-10 w-full sm:mx-auto sm:w-1/2 btn-primary" to="/">
                            Back
                        </RouterLink>
                    </SoundFxEvent>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from 'vue'
import {useAudioStore} from "../stores/audioStore.js";
import SoundFxEvent from "../components/SoundFxEvent.vue";
import Board from "../components/Board.vue";
import Modal from "../components/Modal.vue";
import {PlayerMark} from "../enums/playerMark.js";
import {API} from "../utilities/api.js";

const audio = useAudioStore();
const scores = ref([])
const leaders = ref([])
const loading = ref(true);
const selectedGame = ref(null);
const selectedLeader = ref(null);

async function selectGame(game) {
    audio.playSound('click');
    try {
        const response = await axios.get(`/api/games/${game.game_id}`);
        selectedGame.value = response.data;
    } catch (err) {
        console.error('Failed to fetch game details:', err);
    }
}

function buildCells(game) {
    const total = game.board_size * game.board_size;
    const board = Array(total).fill('');
    for (const move of game.moves) {
        const index = move.row * game.board_size + move.col;
        board[index] = move.mark;
    }
    return board;
}

function getWinningLine(game) {
    const s = game.board_size;
    const cells = buildCells(game);
    const lines = [];

    // rows
    for (let r = 0; r < s; r++) lines.push(Array.from({ length: s }, (_, i) => r * s + i));
    // cols
    for (let c = 0; c < s; c++) lines.push(Array.from({ length: s }, (_, i) => i * s + c));
    // diags
    lines.push(Array.from({ length: s }, (_, i) => i * (s + 1)));
    lines.push(Array.from({ length: s }, (_, i) => (i + 1) * (s - 1)));

    for (const L of lines) {
        const first = cells[L[0]];
        if (first && L.every(i => cells[i] === first)) return L;
    }
    return null;
}

const funMessage = computed(() => {
    const w = selectedLeader.value.games_won
    const l = selectedLeader.value.games_lost

    if (w + l < 3) return 'New challenger approaches! 🔥'
    if (selectedLeader.value.win_percentage === 100) return 'Undefeated!'
    if (selectedLeader.value.win_percentage >= 80) return 'A true Tic-Tac-Toe master 🧠'
    if (selectedLeader.value.win_percentage >= 60) return 'They’ve got some serious skills! 😎'
    if (selectedLeader.value.win_percentage >= 40) return 'Holding strong! 💪'
    if (selectedLeader.value.win_percentage >= 20) return 'Practice makes perfect! 🎯'
    return 'Learning the ropes 👶'
})

onMounted(async () => {
    document.querySelector('html').classList.add('safe-area');
    try {
        const {data} = await axios.get('/api/games')
        scores.value = data
    } catch (error) {
        console.error('Failed to fetch scores:', error)
    } finally {
        setTimeout(() => loading.value = false, 1000)
    }

    // const players = await axios.get('/api/leaderboard')
    // console.log(players.data)

    try {
        const {data} = await axios.get('/api/leaderboard/top-three')
        leaders.value = data
        // console.log(data)
    } catch (error) {
        console.error('Failed to fetch top three leaders:', error)
    }
})
onUnmounted(() => document.querySelector('html').classList.remove('safe-area'))
</script>
