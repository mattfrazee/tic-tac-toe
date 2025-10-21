<template>
    <div class="safe-area-container h-screen">
        <PlayerNames v-if="!hasNames" @submit="setNames"/>
        <template v-else>
            <div class="flex flex-col justify-between h-full bg-[#0b0b1a] text-white">
                <ScoreBoard :playerO="playerO" :score="score" :turn="turn"/>

                <div class="flex-1 flex justify-center items-center">
                    <Board :cells="cells" :turn="turn" :winningLine="winningLine" @play="play"/>
                </div>

                <ScoreBoard :playerX="playerX" :score="score" :turn="turn"/>
            </div>
            <GameOverModal v-if="winner" :winner="winner" :playerX="playerX" :playerO="playerO" @again="reset"/>
        </template>
        <OrientationWarning />
    </div>
</template>
<script setup>
import {onMounted, onUnmounted, ref} from 'vue'
import Board from '../components/Board.vue'
import ScoreBoard from '../components/ScoreBoard.vue'
import PlayerNames from '../components/PlayerNames.vue'
import GameOverModal from '../components/GameOverModal.vue'
import {useAudioStore} from "../stores/audioStore.js";
import OrientationWarning from "../components/OrientationWarning.vue";

const audio = useAudioStore();
const cells = ref(Array(9).fill(null));
const turn = ref(Math.random() < 0.5 ? 'X' : 'O');
const winner = ref(null);
const winningLine = ref(null);
const playerX = ref('Player X');
const playerO = ref('Player O');
const score = ref({X: 0, O: 0});
const hasNames = ref(false);

function setNames({x, o}) {
    playerX.value = x;
    playerO.value = o;
    hasNames.value = true;
}

function play(i) {
    if (cells.value[i]) {
        audio.playSound('wrong');
    }
    if (cells.value[i] || winner.value) {
        return;
    }
    cells.value[i] = turn.value;
    audio.playSound('move3');
    check();
    turn.value = turn.value === 'X' ? 'O' : 'X';
}

function lines() {
    return [[0, 1, 2], [3, 4, 5], [6, 7, 8], [0, 3, 6], [1, 4, 7], [2, 5, 8], [0, 4, 8], [2, 4, 6]]
}

function check() {
    for (const L of lines()) {
        const [a, b, c] = L;
        const v = cells.value
        if (v[a] && v[a] === v[b] && v[b] === v[c]) {
            winner.value = v[a];
            winningLine.value = L;
            score.value[winner.value]++;

            saveGame();
            return;
        }
    }
    if (cells.value.every(Boolean)) {
        winner.value = 'draw';
        saveGame();
    }
}

function reset() {
    cells.value = Array(9).fill(null);
    winner.value = null;
    winningLine.value = null;
    turn.value = Math.random() < 0.5 ? 'X' : 'O'
}

async function saveGame() {
    try {
        await axios.post('/api/games', {
            player_x_name: playerX.value,
            player_o_name: playerO.value,
            first_player: turn.value,
            winner: winner.value,
            moves: cells.value.map((mark, idx) => ({
                mark,
                row: Math.floor(idx / 3),
                col: idx % 3,
            })).filter(m => m.mark)
        })
    } catch (e) {
        console.error('Failed to save game', e)
    }
}

onMounted(() => document.querySelector('html').classList.add('non-scrollable', 'safe-area'));
onUnmounted(() => document.querySelector('html').classList.remove('non-scrollable', 'safe-area'));
</script>
