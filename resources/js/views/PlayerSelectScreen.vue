<template>
    <div class="safe-area-container h-screen">
        <PlayerNames v-if="!startedGame" @submit="startGame"/>
        <template v-else>
            <div class="flex flex-col justify-between h-full bg-[#0b0b1a] text-white">
                <ScoreBoard :playerO="game.playerO" :score="game.score" :turn="game.turn" v-if="game.vsComputer || ! game.isOnline"/>

                <div class="flex-1 flex justify-center items-center">
                    <Board :cells="game.cells"
                           :size="game.size"
                           :turn="game.turn"
                           :winningLine="game.winningLine"
                           @play="game.play"/>
                </div>

                <ScoreBoard :playerX="game.playerX" :score="game.score" :turn="game.turn"/>
            </div>

            <Modal :title="game.winner === PlayerMark.DRAW ? 'Draw!' :`${ game.winner === PlayerMark.X ? game.playerX : game.playerO} wins!`"
                   :closable-backdrop="false"
                   :visible="!! game.winner"
                   @close="game.winner = null">
                <template #actions>
                    <div class="flex gap-4 mt-4 flex-col w-full">
                        <SoundFxEvent autoplay file="win3"/>
                        <SoundFxEvent file="playerSelect">
                            <button class="btn-primary btn-glow w-5/6 mx-auto" @click="restartGame">
                                Play Again
                            </button>
                        </SoundFxEvent>
                        <SoundFxEvent file="click">
                            <RouterLink class="btn-secondary btn-small mx-auto" to="/" @click="resetGame">
                                Main Menu
                            </RouterLink>
                        </SoundFxEvent>
                    </div>
                </template>
            </Modal>

        </template>
        <OrientationWarning/>
    </div>
</template>
<script setup>
import {nextTick, onMounted, onUnmounted, ref} from 'vue'
import Board from '../components/Board.vue'
import ScoreBoard from '../components/ScoreBoard.vue'
import PlayerNames from '../components/PlayerNames.vue'
import OrientationWarning from "../components/OrientationWarning.vue";
import Modal from "../components/Modal.vue";
import SoundFxEvent from "../components/SoundFxEvent.vue";
import {PlayerMark} from "../enums/playerMark.js";
import {useGameStore} from "../stores/gamePlayStore.js";

const game = useGameStore()
const startedGame = ref(false);

function startGame({x, o, gridSize, vsComputerMode, roomCode}) {
    game.playerX = x;
    game.playerO = o ?? null;
    game.size = gridSize;
    game.vsComputer = vsComputerMode ?? false;
    game.roomCode = roomCode ?? null;
    startedGame.value = true;

    nextTick(() => {
        if (game.vsComputer && game.turn === PlayerMark.O) {
            setTimeout(game.aiMove, 500);
        }
    });
}

function restartGame() {
    resetGame()
    startGame({
        x: game.playerX,
        o: game.playerO,
        gridSize: game.size,
        vsComputerMode: game.vsComputer
    })
}

function resetGame() {
    game.cells = Array(game.size * game.size).fill(null);
    game.winner = null;
    game.winningLine = null;
    game.roomCode = null;
    game.turn = game.randomPlayerMark();
}

onMounted(() => document.querySelector('html').classList.add('non-scrollable', 'safe-area'));
onUnmounted(() => document.querySelector('html').classList.remove('non-scrollable', 'safe-area'));
</script>
